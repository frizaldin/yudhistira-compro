<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventQuestionAnswer;
use App\Models\EventTeacherHub;
use App\Models\ProductDocument;
use App\Models\VideoLearning;
use App\Models\VideoLearningCompletion;
use App\Models\RedeemCode;
use App\Models\RedeemCodeMember;
use App\Models\TeacherBook;
use App\Models\TeacherBookmark;
use App\Models\UserPoint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TeacherBookApiController extends Controller
{
    private function buyBukuHeaders(): array
    {
        return [
            'key' => env('API_KEY'),
            'Origin' => 'buy.bukuyudhistira.id'
        ];
    }

    private function buyBukuBaseUrl(): string
    {
        return env('API_URL');
    }

    private function fetchServiceProductIds(string $endpoint, array $params = []): array
    {
        try {
            $baseUrl = rtrim($this->buyBukuBaseUrl(), '/') . '/' . ltrim($endpoint, '/');
            $ids     = [];
            $page    = 1;

            // Fetch all pages to collect all matching product IDs
            do {
                $queryParams = array_merge($params, ['page' => $page]);

                $response = Http::withHeaders($this->buyBukuHeaders())
                    ->withoutVerifying()
                    ->timeout(15)
                    ->get($baseUrl, $queryParams);

                if (!$response->successful()) {
                    break;
                }

                $body     = $response->json();
                $wrapper  = $body['data'] ?? [];

                // Handle both nested pagination (data.data) and flat array
                if (isset($wrapper['data']) && is_array($wrapper['data'])) {
                    $rows      = $wrapper['data'];
                    $lastPage  = (int) ($wrapper['last_page'] ?? 1);
                } elseif (is_array($wrapper)) {
                    $rows      = $wrapper;
                    $lastPage  = 1; // flat list, no pagination
                } else {
                    break;
                }

                foreach ($rows as $row) {
                    $id = $row['id'] ?? $row['product_id'] ?? null;
                    if ($id !== null) {
                        $ids[] = $id;
                    }
                }

                $page++;
            } while ($page <= $lastPage);

            return array_values(array_unique($ids));
        } catch (\Throwable $e) {
            return [];
        }
    }


    /**
     * GET /api/v1/teacher-books/products
     * List produk buku dari API eksternal (buy.bukuyudhistira.id).
     */
    public function products(Request $request): JsonResponse
    {
        $baseUrl = $this->buyBukuBaseUrl();
        $url = $baseUrl . '/products';
        $query = $request->query();

        try {
            $response = Http::withHeaders($this->buyBukuHeaders())
                ->withoutVerifying()   // bypass SSL cert verification (local dev & some server configs)
                ->timeout(15)
                ->get($url, $query);

            if (!$response->successful()) {
                return response()->json([
                    'code'    => $response->status(),
                    'success' => false,
                    'message' => 'Gagal mengambil data produk dari server BuyBuku.',
                ], $response->status());
            }

            return response()->json($response->json());
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return response()->json([
                'code'    => 503,
                'success' => false,
                'message' => 'Tidak dapat terhubung ke server BuyBuku: ' . $e->getMessage(),
            ], 503);
        } catch (\Throwable $e) {
            return response()->json([
                'code'    => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/v1/teacher-books/redeem
     * Redeem kode member dari tabel redeem_code_members (internal).
     */
    public function redeem(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:255',
        ]);

        $code    = trim($request->input('code'));
        $teacher = Auth::guard('api')->user();

        if (!$teacher) {
            return response()->json([
                'code'    => 401,
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        // Cek apakah guru sudah pernah memakai kode ini
        $existing = TeacherBook::where('user_id', $teacher->id)
            ->where('code', $code)
            ->first();

        if ($existing) {
            return response()->json([
                'code'    => 400,
                'success' => false,
                'message' => 'Kode ini sudah pernah digunakan oleh akun Anda.',
            ], 400);
        }

        // Cari kode di tabel redeem_code_members
        $memberCode = RedeemCodeMember::where('code', $code)->first();

        if (!$memberCode) {
            return response()->json([
                'code'    => 400,
                'success' => false,
                'message' => 'Kode tidak valid.',
            ], 400);
        }

        if ($memberCode->used === 'yes') {
            return response()->json([
                'code'    => 400,
                'success' => false,
                'message' => 'Kode sudah pernah digunakan.',
            ], 400);
        }

        // Ambil detail serial code ebook jika ada
        $ebookCodes = [];
        if (!empty($memberCode->serial_code_ebook)) {
            $ebookCodes = RedeemCode::whereIn('id', (array) $memberCode->serial_code_ebook)
                ->get(['id', 'book_title', 'serial_code'])
                ->toArray();
        }

        // Ambil dokumen produk berdasarkan book_id
        $documents = ProductDocument::where('product_id', $memberCode->book_id)
            ->orderByDesc('created_at')
            ->get(['id', 'product_id', 'type', 'attachment', 'json_product'])
            ->toArray();

        // Tandai kode sebagai sudah dipakai
        $memberCode->update(['used' => 'yes']);

        // Simpan ke teacher_books beserta ebook codes dan dokumen
        TeacherBook::create([
            'user_id'    => $teacher->id,
            'code'       => $code,
            'book_id'    => $memberCode->book_id,
            'code_ebook' => $ebookCodes ?: null,
            'document'   => $documents  ?: null,
        ]);

        return response()->json([
            'code'    => 200,
            'success' => true,
            'message' => 'Kode berhasil ditukar.',
            'data'    => [
                'book'        => $memberCode->book_json,
                'book_id'     => $memberCode->book_id,
                'ebook_codes' => $ebookCodes,
                'documents'   => $documents,
            ],
        ]);
    }

    /**
     * GET /api/v1/teacher-books/my-books
     * Daftar kode yang sudah di-redeem oleh guru yang login. Butuh auth teacher.
     */
    public function myBooks(Request $request): JsonResponse
    {
        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json([
                'code' => 401,
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        $productType = $request->query('product_type');
        $allowedBookIds = null;

        // Only forward filter params — NOT 'page', since fetchServiceProductIds handles all pages internally
        $forwardParams = $request->only(['category_id', 'sub_category_id', 'category_secondary_id', 'search']);
        $forwardParams = array_filter($forwardParams, fn($v) => $v !== null && $v !== '');

        if ($productType === 'produk_buku') {
            $allowedBookIds = $this->fetchServiceProductIds('products', $forwardParams);
        } elseif ($productType === 'produk_digital') {
            $allowedBookIds = $this->fetchServiceProductIds('digital-products', $forwardParams);
        }

        $query = TeacherBook::where('user_id', $teacher->id)
            ->orderBy('created_at', 'desc');

        if ($allowedBookIds !== null) {
            $query->whereIn('book_id', $allowedBookIds);
        }

        $items = $query->get();

        $bookIds = $items->pluck('book_id')->filter()->unique()->values();

        $documents = ProductDocument::whereIn('product_id', $bookIds)
            ->orderByDesc('created_at')
            ->get(['id', 'product_id', 'type', 'attachment', 'json_product'])
            ->groupBy('product_id');

        $data = $items->map(function ($item) use ($documents) {
            $arr = $item->toArray();
            $arr['document'] = $documents->get($item->book_id, collect())->values()->toArray() ?: null;
            return $arr;
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * GET /api/v1/teacher-books/my-events
     */
    public function myEvents(Request $request): JsonResponse
    {
        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json([
                'code' => 401,
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        $items = EventQuestionAnswer::where('user_id', $teacher->id)
            ->with(['event', 'question'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * GET /api/v1/teacher-books/my-points
     */
    public function myPoints(Request $request): JsonResponse
    {
        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json([
                'code' => 401,
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        $items = UserPoint::where('user_id', $teacher->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * GET /api/v1/teacher-books/dashboard-count
     * Ringkasan dashboard: jumlah buku, total poin (IN - OUT), jumlah event diikuti.
     */
    public function dashboardCount(Request $request): JsonResponse
    {
        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json([
                'code' => 401,
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        $booksCount = TeacherBook::where('user_id', $teacher->id)->count();

        $pointIn = UserPoint::where('user_id', $teacher->id)->where('type', 'IN')->sum('point');
        $pointOut = UserPoint::where('user_id', $teacher->id)->where('type', 'OUT')->sum('point');
        $point = $pointIn - $pointOut;

        $eventsCount = EventQuestionAnswer::where('user_id', $teacher->id)->pluck('event_id')->unique()->count();

        $followingEventsCount = TeacherBookmark::where('user_id', $teacher->id)
            ->where('type', TeacherBookmark::TYPE_EVENT)
            ->count();

        $followingVideoLearningsCount = TeacherBookmark::where('user_id', $teacher->id)
            ->where('type', TeacherBookmark::TYPE_VIDEO_LEARNING)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'books_count'                   => $booksCount,
                'point'                         => $point,
                'events_count'                  => $eventsCount,
                'following_events_count'        => $followingEventsCount,
                'following_video_learnings_count' => $followingVideoLearningsCount,
            ],
        ]);
    }

    /**
     * GET /api/v1/teacher-books/my-event
     * Daftar event yang di-follow (bookmark) oleh guru yang login.
     */
    public function myEvent(Request $request): JsonResponse
    {
        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json(['code' => 401, 'success' => false, 'message' => 'Unauthorized.'], 401);
        }

        $referenceIds = TeacherBookmark::where('user_id', $teacher->id)
            ->where('type', TeacherBookmark::TYPE_EVENT)
            ->pluck('reference_id');

        $items = EventTeacherHub::with('category')
            ->whereIn('id', $referenceIds)
            ->orderByDesc('date')
            ->get();

        return response()->json(['success' => true, 'data' => $items]);
    }

    /**
     * GET /api/v1/teacher-books/my-learning
     * Daftar video learning yang di-follow (bookmark) oleh guru yang login.
     */
    public function myLearning(Request $request): JsonResponse
    {
        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json(['code' => 401, 'success' => false, 'message' => 'Unauthorized.'], 401);
        }

        $referenceIds = TeacherBookmark::where('user_id', $teacher->id)
            ->where('type', TeacherBookmark::TYPE_VIDEO_LEARNING)
            ->pluck('reference_id');

        $items = VideoLearning::with('category')
            ->whereIn('id', $referenceIds)
            ->orderBy('sort_order')
            ->get();

        return response()->json(['success' => true, 'data' => $items]);
    }

    /**
     * GET /api/v1/teacher-books/my-event-completed
     * Daftar event/webinar yang telah diselesaikan (sudah submit jawaban kuis).
     */
    public function myEventCompleted(Request $request): JsonResponse
    {
        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json(['code' => 401, 'success' => false, 'message' => 'Unauthorized.'], 401);
        }

        $completedEventIds = EventQuestionAnswer::where('user_id', $teacher->id)
            ->pluck('event_id')
            ->unique()
            ->values();

        $completedAt = EventQuestionAnswer::where('user_id', $teacher->id)
            ->whereIn('event_id', $completedEventIds)
            ->selectRaw('event_id, MIN(created_at) as completed_at')
            ->groupBy('event_id')
            ->pluck('completed_at', 'event_id');

        $items = EventTeacherHub::with('category')
            ->whereIn('id', $completedEventIds)
            ->orderByDesc('date')
            ->get()
            ->map(function ($event) use ($completedAt) {
                $arr = $event->toArray();
                $arr['completed_at'] = $completedAt[$event->id] ?? null;
                return $arr;
            });

        return response()->json(['success' => true, 'data' => $items]);
    }

    /**
     * GET /api/v1/teacher-books/my-learning-completed
     * Daftar video learning yang telah diselesaikan (semua video + kuis selesai).
     */
    public function myLearningCompleted(Request $request): JsonResponse
    {
        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json(['code' => 401, 'success' => false, 'message' => 'Unauthorized.'], 401);
        }

        $completions = VideoLearningCompletion::where('user_id', $teacher->id)
            ->orderByDesc('completed_at')
            ->get()
            ->keyBy('video_learning_id');

        $items = VideoLearning::with('category')
            ->whereIn('id', $completions->keys())
            ->get()
            ->map(function ($learning) use ($completions) {
                $arr = $learning->toArray();
                $completion = $completions[$learning->id] ?? null;
                $arr['completed_at']  = $completion?->completed_at;
                $arr['point_awarded'] = $completion?->point_awarded;
                return $arr;
            })
            ->sortByDesc('completed_at')
            ->values();

        return response()->json(['success' => true, 'data' => $items]);
    }
}

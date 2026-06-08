<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RequestBook;
use App\Models\Configuration;
use App\Models\RequestBookDetail;
use App\Services\BuyBukuProductCatalog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RequestBookApiController extends Controller
{
    /**
     * POST /api/request-books/create
     * Ajukan permintaan buku (maksimal 6 book_id).
     */
    public function store(Request $request): JsonResponse
    {
        $count = count($request->book_ids);
        
        if ($count > 6) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Maksimal 6 Buku'
            ], 500);
        }

        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json([
                'code' => 401,
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        
        $requestBook = DB::transaction(function () use ($teacher, $request) {
            $number = 'RBREQ' . now()->format('Ymd') . strtoupper(Str::random(8));

            $rb = RequestBook::create([
                'user_id' => $teacher->id,
                'number' => $number,
                'date' => now()->toDateString(),
                'status' => 'pending',
                'code_book' => null,
            ]);

            foreach ($request->book_ids as $bookId) {
                RequestBookDetail::create([
                    'request_book_id' => $rb->id,
                    'book_id' => $bookId['id'],
                     'book' => $bookId,
                ]);
            }

            return $rb->load('details');
        });

        $this->enrichDetailsWithProductMeta(collect([$requestBook]));

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Permintaan buku berhasil dikirim. Menunggu proses admin.',
            'data' => $requestBook,
        ]);
    }

    /**
     * GET /api/request-books
     */
    public function index(Request $request): JsonResponse
    {
        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json([
                'code' => 401,
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        $perPage = (int) request()->get('per_page', 20);

        $items = RequestBook::where('user_id', $teacher->id)
            ->with('details')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends($request->except('page'));

        // $this->enrichDetailsWithProductMeta($items);
        
        $today = date('Y-m-d');
        
        
        $configuration = Configuration::select('max_request', 'start_request', 'end_request')->orderBy('id', 'desc')->first();
        $max_request = $teacher->max_request ?? $configuration->max_request;
        
        $nowMonth   = date('m');
        $startMonth = date('m', strtotime($configuration->start_request));
    
        if ($nowMonth > $startMonth) {
            $start_request = date('Y-m-d', strtotime((date('Y') - 1) . '-' . $configuration->start_request));
            $end_request   = date('Y-m-d', strtotime(date('Y') . '-' . $configuration->end_request));
        } else {
            $start_request = date('Y-m-d', strtotime(date('Y') . '-' . $configuration->start_request));
            $end_request   = date('Y-m-d', strtotime(date('Y') . '-' . $configuration->end_request . ' +1 year'));
        }
        
        $requested = RequestBook::where('user_id', $teacher->id)
            ->whereBetween('date', [$start_request, $end_request])
            ->whereIn('status', ['pending','processed'])
            ->count();
        
        $set = [
            'start_request' => $start_request,
            'end_request' => $end_request,
            'max_request' => $max_request,
            'requested' => $requested,
            'attempt' => $max_request - $requested
        ];

        return response()->json([
            'success' => true,
            'configuration' => $set,
            'data' => $items,
        ]);
    }

    /**
     * GET /api/request-books/{id}
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json([
                'code' => 401,
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        $item = RequestBook::where('user_id', $teacher->id)
            ->with('details')
            ->find($id);

        if (!$item) {
            return response()->json([
                'code' => 404,
                'success' => false,
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }
        
        $item['code_book_url'] = asset($item['code_book']);

        // $this->enrichDetailsWithProductMeta(collect([$item]));

        return response()->json([
            'success' => true,
            'data' => $item,
        ]);
    }

    /**
     * Tambahkan book_title & book_thumbnail pada setiap detail dari katalog API buy buku.
     *
     * @param  \Illuminate\Support\Collection<int, RequestBook>  $requestBooks
     */
    private function enrichDetailsWithProductMeta($requestBooks): void
    {
        if ($requestBooks instanceof \Illuminate\Pagination\AbstractPaginator) {
            $requestBooks = $requestBooks->getCollection();
        }
    
        $ids = [];
        foreach ($requestBooks as $rb) {
            foreach ($rb->details as $d) {
                $ids[] = (int) $d->book_id;
            }
        }
    
        $meta = app(BuyBukuProductCatalog::class)->metaForIds($ids);
    
        foreach ($requestBooks as $rb) {
            foreach ($rb->details as $d) {
                $m = $meta[(int) $d->book_id] ?? null;
                $d->setAttribute('book_title', $m['title'] ?? null);
                $d->setAttribute('book_thumbnail', $m['file'] ?? null);
            }
        }
    }
}

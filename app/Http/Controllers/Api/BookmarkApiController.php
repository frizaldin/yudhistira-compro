<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Concerns\FormatsPhotoUrl;
use App\Models\TeacherBookmark;
use App\Models\EventTeacherHub;
use App\Models\VideoLearning;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkApiController extends Controller
{
    use FormatsPhotoUrl;

    /**
     * GET /api/v1/bookmarks
     * Daftar event & video learning yang di-bookmark/ikuti oleh guru yang login.
     * Params: status = all | incoming | following | done
     * - all: semua bookmark
     * - incoming: event date >= hari ini (akan datang) + semua video_learning
     * - following: sama dengan incoming
     * - done: hanya event date < hari ini (sudah lewat)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $teacher = Auth::guard('api')->user();
            if (!$teacher) {
                return response()->json([
                    'code' => 401,
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $status = $request->query('status', 'all');
            if (!in_array($status, ['all', 'incoming', 'following', 'done'], true)) {
                $status = 'all';
            }

            $today = today()->format('Y-m-d');

            $bookmarks = TeacherBookmark::where('user_id', $teacher->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $eventIds = $bookmarks->where('type', TeacherBookmark::TYPE_EVENT)->pluck('reference_id')->unique()->values();
            $videoIds = $bookmarks->where('type', TeacherBookmark::TYPE_VIDEO_LEARNING)->pluck('reference_id')->unique()->values();

            $events = EventTeacherHub::with('category')->whereIn('id', $eventIds)->get()->keyBy('id');
            $videoLearnings = VideoLearning::with('category')->whereIn('id', $videoIds)->get()->keyBy('id');

            $data = [];
            foreach ($bookmarks as $b) {
                if ($b->type === TeacherBookmark::TYPE_EVENT) {
                    $item = $events->get($b->reference_id);
                    if (!$item) {
                        continue;
                    }
                    $eventDate = $item->date ? (is_string($item->date) ? $item->date : \Carbon\Carbon::parse($item->date)->format('Y-m-d')) : null;
                    if ($status === 'incoming' || $status === 'following') {
                        if ($eventDate === null || $eventDate < $today) {
                            continue;
                        }
                    }
                    if ($status === 'done') {
                        if ($eventDate === null || $eventDate >= $today) {
                            continue;
                        }
                    }
                    $data[] = [
                        'bookmark_id' => $b->id,
                        'type' => 'event_teacher_hub',
                        'id' => $item->id,
                        'category_id' => $item->category_id,
                        'category' => $item->category ? [
                            'id' => $item->category->id,
                            'title' => $item->category->title,
                            'judul' => $item->category->judul ?? null,
                        ] : null,
                        'photo' => $this->formatPhoto($item->photo),
                        'title' => $item->title,
                        'judul' => $item->judul,
                        'date' => $item->date,
                        'start_time' => $item->start_time,
                        'end_time' => $item->end_time,
                        'point' => $item->point,
                        'link_meeting' => $item->link_meeting,
                        'created_at' => $item->created_at?->toIso8601String(),
                    ];
                } else {
                    if ($status === 'done') {
                        continue;
                    }
                    $item = $videoLearnings->get($b->reference_id);
                    if (!$item) {
                        continue;
                    }
                    if ($status === 'incoming' || $status === 'following') {
                        // video_learning selalu masuk incoming/following (konten tersedia)
                    }
                    $thumbUrl = $item->thumbnail;
                    if ($thumbUrl && !str_starts_with($thumbUrl, 'http')) {
                        $thumbUrl = asset($thumbUrl);
                    }
                    $data[] = [
                        'bookmark_id' => $b->id,
                        'type' => 'video_learning',
                        'id' => $item->id,
                        'category_id' => $item->category_id,
                        'category' => $item->category ? [
                            'id' => $item->category->id,
                            'title' => $item->category->title,
                        ] : null,
                        'thumbnail' => $thumbUrl,
                        'title' => $item->title,
                        'judul' => $item->judul,
                        'description' => $item->description,
                        'deskripsi' => $item->deskripsi,
                        'point' => $item->point,
                        'sort_order' => $item->sort_order,
                        'created_at' => $item->created_at?->toIso8601String(),
                    ];
                }
            }

            $perPage = (int) $request->query('per_page', 20);
            $perPage = min(max($perPage, 1), 100);
            $page = (int) $request->query('page', 1);
            $total = count($data);
            $chunk = array_slice($data, ($page - 1) * $perPage, $perPage);

            return response()->json([
                'success' => true,
                'data' => $chunk,
                'meta' => [
                    'current_page' => $page,
                    'last_page' => (int) max(1, ceil($total / $perPage)),
                    'per_page' => $perPage,
                    'total' => $total,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * POST /api/v1/bookmarks
     * Body: { "type": "event_teacher_hub" | "video_learning", "id": 1 }
     * Bookmark / ikuti event atau video learning.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $teacher = Auth::guard('api')->user();
            if (!$teacher) {
                return response()->json([
                    'code' => 401,
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $request->validate([
                'type' => 'required|in:event_teacher_hub,video_learning',
                'id' => 'required|integer|min:1',
            ]);

            $type = $request->input('type');
            $referenceId = (int) $request->input('id');

            if ($type === TeacherBookmark::TYPE_EVENT) {
                if (!EventTeacherHub::find($referenceId)) {
                    return response()->json([
                        'code' => 404,
                        'success' => false,
                        'message' => 'Event tidak ditemukan.',
                    ], 404);
                }
            } else {
                if (!VideoLearning::find($referenceId)) {
                    return response()->json([
                        'code' => 404,
                        'success' => false,
                        'message' => 'Video learning tidak ditemukan.',
                    ], 404);
                }
            }

            $bookmark = TeacherBookmark::firstOrCreate(
                [
                    'user_id' => $teacher->id,
                    'type' => $type,
                    'reference_id' => $referenceId,
                ]
            );

            return response()->json([
                'code' => 201,
                'success' => true,
                'message' => $bookmark->wasRecentlyCreated ? 'Berhasil mengikuti/bookmark.' : 'Sudah pernah di-bookmark.',
                'data' => [
                    'bookmark_id' => $bookmark->id,
                    'type' => $bookmark->type,
                    'id' => $bookmark->reference_id,
                ],
            ], $bookmark->wasRecentlyCreated ? 201 : 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'code' => 422,
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /api/v1/bookmarks
     * Body: { "type": "event_teacher_hub" | "video_learning", "id": 1 }
     * Hapus bookmark / unfollow.
     */
    public function destroy(Request $request): JsonResponse
    {
        try {
            $teacher = Auth::guard('api')->user();
            if (!$teacher) {
                return response()->json([
                    'code' => 401,
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $request->validate([
                'type' => 'required|in:event_teacher_hub,video_learning',
                'id' => 'required|integer|min:1',
            ]);

            $type = $request->input('type');
            $referenceId = (int) $request->input('id');

            $deleted = TeacherBookmark::where('user_id', $teacher->id)
                ->where('type', $type)
                ->where('reference_id', $referenceId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => $deleted ? 'Bookmark berhasil dihapus.' : 'Bookmark tidak ditemukan.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'code' => 422,
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}

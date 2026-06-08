<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Concerns\FormatsPhotoUrl;
use App\Models\EventTeacherHub;
use App\Models\VideoLearning;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventAndVideoLearningApiController extends Controller
{
    use FormatsPhotoUrl;

    /**
     * GET /api/v1/event-and-video-learnings
     * Gabungan data Event Teacher Hub + Video Learning dalam satu array.
     * Params: status = all | incoming | following | done
     * - all: semua
     * - incoming: event date >= hari ini (akan datang) + semua video_learning
     * - following: sama dengan incoming
     * - done: hanya event date < hari ini (sudah lewat)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $today = today()->format('Y-m-d');
            $status = $request->query('status', 'all');
            if (!in_array($status, ['all', 'incoming', 'following', 'done'], true)) {
                $status = 'all';
            }

            $eventsQuery = EventTeacherHub::with('category')->orderBy('date', 'desc')->orderBy('start_time', 'desc');
            $videoQuery = VideoLearning::with('category')->orderBy('sort_order')->orderBy('id', 'desc');

            if ($request->filled('search')) {
                $search = '%' . $request->query('search') . '%';
                $eventsQuery->where(function ($q) use ($search) {
                    $q->where('title', 'like', $search)->orWhere('judul', 'like', $search);
                });
                $videoQuery->where(function ($q) use ($search) {
                    $q->where('title', 'like', $search)->orWhere('judul', 'like', $search);
                });
            }

            if ($request->filled('event_category_id')) {
                $eventsQuery->where('category_id', (int) $request->event_category_id);
            }
            if ($request->filled('video_category_id')) {
                $videoQuery->where('category_id', (int) $request->video_category_id);
            }

            if ($status === 'incoming' || $status === 'following') {
                $eventsQuery->where('date', '>=', $today);
            } elseif ($status === 'done') {
                $eventsQuery->where('date', '<', $today);
            }

            $events = $eventsQuery->get();
            $videoLearnings = ($status === 'done') ? collect([]) : $videoQuery->get();

            $eventItems = $events->map(function ($item) {
                return [
                    'type' => 'event',
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
            });

            $videoItems = $videoLearnings->map(function ($item) {
                $thumbUrl = $item->thumbnail;
                if ($thumbUrl && !str_starts_with($thumbUrl, 'http')) {
                    $thumbUrl = asset($thumbUrl);
                }
                return [
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
            });

            $merged = $eventItems->concat($videoItems->all());

            if ($request->filled('sort') && $request->sort === 'created_at') {
                $merged = $merged->sortByDesc(function ($item) {
                    return $item['created_at'] ?? '';
                })->values();
            }

            $perPage = (int) $request->query('per_page', 20);
            $perPage = min(max($perPage, 1), 100);
            $page = (int) $request->query('page', 1);
            $total = $merged->count();
            $chunk = $merged->forPage($page, $perPage)->values();

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
}

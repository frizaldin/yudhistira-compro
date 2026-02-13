<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Concerns\FormatsPhotoUrl;
use App\Models\BlogTeacherHub;
use App\Models\EventTeacherHub;
use App\Models\CategoryTeacherHub;
use App\Models\CategoryEventTeacherHub;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BlogAndEventTeacherHubApiController extends Controller
{
    use FormatsPhotoUrl;

    /**
     * GET /api/v1/blog-and-event-teacher-hubs
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 20);
        $search = $request->get('search');

        $blogQuery = BlogTeacherHub::where('visible', 'yes')
            ->with('category')
            ->orderBy('date', 'desc');

        if ($request->category_id) {
            $blogQuery->where('category_id', $request->category_id);
        }

        if ($search) {
            $blogQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        $blogItems = $blogQuery->get();
        $blogs = $blogItems->map(function ($item) {
            return array_merge(
                [
                    'type' => 'blog',
                    'id' => $item->id,
                    'category_id' => $item->category_id,
                    'category' => $item->category ? [
                        'id' => $item->category->id,
                        'title' => $item->category->title,
                        'judul' => $item->category->judul,
                    ] : null,
                    'name' => $item->name,
                    'nama' => $item->nama,
                    'photo' => $this->formatPhoto($item->photo),
                    'overview' => $item->overview,
                    'pratinjau' => $item->pratinjau,
                    'description' => $item->description,
                    'deskripsi' => $item->deskripsi,
                    'tags' => $item->tags,
                    'url' => $item->url,
                    'date' => $item->date,
                    'created_at' => $item->created_at?->toIso8601String(),
                ],
                ['_sort_date' => $item->date ? strtotime($item->date) : 0]
            );
        });

        $eventQuery = EventTeacherHub::with('category')->orderBy('date', 'desc');

        if ($search) {
            $eventQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('judul', 'like', '%' . $search . '%');
            });
        }

        $eventItems = $eventQuery->get();
        $events = $eventItems->map(function ($item) {
            return array_merge(
                [
                    'type' => 'event',
                    'id' => $item->id,
                    'category_id' => $item->category_id,
                    'category' => $item->category ? [
                        'id' => $item->category->id,
                        'title' => $item->category->title,
                        'judul' => $item->category->judul,
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
                ],
                ['_sort_date' => $item->date ? strtotime($item->date) : 0]
            );
        });

        $merged = $blogs->concat($events)
            ->sortByDesc('_sort_date')
            ->values()
            ->map(function ($item) {
                unset($item['_sort_date']);
                return $item;
            });

        $total = $merged->count();
        $data = $merged->take($perPage)->values()->toArray();

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'total' => $total,
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * GET /api/v1/category-blog-and-event-teacher-hubs
     */
    public function categories(Request $request): JsonResponse
    {
        $blogCategories = CategoryTeacherHub::orderBy('id')
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'type' => 'blog',
                    'title' => $cat->title,
                    'judul' => $cat->judul,
                ];
            });

        $eventCategories = CategoryEventTeacherHub::orderBy('id')
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'type' => 'event',
                    'title' => $cat->title,
                    'judul' => $cat->judul,
                ];
            });

        $categories = $blogCategories->concat($eventCategories)->values();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
}

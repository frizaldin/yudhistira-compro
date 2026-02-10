<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogTeacherHub;
use App\Models\CategoryTeacherHub;
use App\Models\AnnouncementTeacherHub;
use App\Models\CategoryAnnouncementTeacherHub;
use App\Models\EventTeacherHub;
use App\Models\TeacherReward;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TeacherHubApiController extends Controller
{
    /**
     * Format photo URL ke full path
     */
    private function formatPhoto(?string $path): ?string
    {
        return $path ? asset($path) : null;
    }

    /**
     * GET /api/v1/blog-teacher-hubs
     */
    public function blogTeacherHubs(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $query = BlogTeacherHub::where('visible', 'yes')
            ->with('category')
            ->orderBy('date', 'desc');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        $items = $query->paginate($perPage);
        $data = $items->getCollection()->map(function ($item) {
            return [
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
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    /**
     * GET /api/v1/blog-teacher-hubs/{id}
     */
    public function blogTeacherHubDetail(int $id): JsonResponse
    {
        $item = BlogTeacherHub::where('visible', 'yes')->with('category')->find($id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
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
        ]);
    }

    /**
     * GET /api/v1/category-teacher-hubs
     */
    public function categoryTeacherHubs(Request $request): JsonResponse
    {
        $query = CategoryTeacherHub::where('visible', 'yes')->orderBy('order');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('judul', 'like', '%' . $search . '%');
            });
        }

        $items = $query->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'judul' => $item->judul,
                    'file' => $this->formatPhoto($item->file),
                    'url' => $item->url,
                    'order' => $item->order,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * GET /api/v1/announcement-teacher-hubs
     */
    public function announcementTeacherHubs(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $query = AnnouncementTeacherHub::where('visible', 'yes')
            ->with('category')
            ->orderBy('date', 'desc');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nama', 'like', '%' . $search . '%');
            });
        }

        $items = $query->paginate($perPage);
        $data = $items->getCollection()->map(function ($item) {
            return [
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
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    /**
     * GET /api/v1/announcement-teacher-hubs/{id}
     */
    public function announcementTeacherHubDetail(int $id): JsonResponse
    {
        $item = AnnouncementTeacherHub::where('visible', 'yes')->with('category')->find($id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
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
        ]);
    }

    /**
     * GET /api/v1/category-announcement-teacher-hubs
     */
    public function categoryAnnouncementTeacherHubs(Request $request): JsonResponse
    {
        $query = CategoryAnnouncementTeacherHub::where('visible', 'yes')->orderBy('order');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('judul', 'like', '%' . $search . '%');
            });
        }

        $items = $query->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'judul' => $item->judul,
                    'file' => $this->formatPhoto($item->file),
                    'url' => $item->url,
                    'order' => $item->order,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * GET /api/v1/event-teacher-hubs
     */
    public function eventTeacherHubs(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $query = EventTeacherHub::orderBy('date', 'desc');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('judul', 'like', '%' . $search . '%');
            });
        }

        $items = $query->paginate($perPage);
        $data = $items->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
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

        return response()->json([
            'success' => true,
            'data' => $data,
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    /**
     * GET /api/v1/teacher-rewards
     */
    public function teacherRewards(Request $request): JsonResponse
    {
        $query = TeacherReward::orderBy('id');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('judul', 'like', '%' . $search . '%');
            });
        }

        $items = $query->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'photo' => $this->formatPhoto($item->photo),
                    'title' => $item->title,
                    'judul' => $item->judul,
                    'point' => $item->point,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Concerns\FormatsPhotoUrl;
use App\Models\BlogTeacherHub;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BlogTeacherHubApiController extends Controller
{
    use FormatsPhotoUrl;

    /**
     * GET /api/v1/blog-teacher-hubs
     */
    public function index(Request $request): JsonResponse
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
    public function show(int $id): JsonResponse
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
}

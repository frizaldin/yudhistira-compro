<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Concerns\FormatsPhotoUrl;
use App\Models\GuideBook;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GuideBookApiController extends Controller
{
    use FormatsPhotoUrl;

    /**
     * GET /api/v1/guide-books
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $query = GuideBook::with('category')->orderBy('id', 'desc');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->category) {
            $query->whereRelation('category', 'title', 'like', '%'.$request->category.'%');
        }

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
                'category_id' => $item->category_id,
                'category' => $item->category ? [
                    'id' => $item->category->id,
                    'title' => $item->category->title,
                    'judul' => $item->category->judul,
                ] : null,
                'thumbnail' => $this->formatPhoto($item->thumbnail),
                'title' => $item->title,
                'judul' => $item->judul,
                'file' => $this->formatPhoto($item->file),
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
     * GET /api/v1/guide-books/{id}
     */
    public function show(int $id): JsonResponse
    {
        $item = GuideBook::with('category')->find($id);
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
                'thumbnail' => $this->formatPhoto($item->thumbnail),
                'title' => $item->title,
                'judul' => $item->judul,
                'file' => $this->formatPhoto($item->file),
                'created_at' => $item->created_at?->toIso8601String(),
            ],
        ]);
    }
}

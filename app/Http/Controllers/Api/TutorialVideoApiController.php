<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TutorialVideo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TutorialVideoApiController extends Controller
{
    /**
     * GET /api/v1/tutorial-videos
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = TutorialVideo::orderBy('sort_order')->orderBy('id', 'desc');

            if ($request->filled('search')) {
                $search = '%' . $request->query('search') . '%';
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', $search)->orWhere('judul', 'like', $search);
                });
            }

            $perPage = (int) $request->query('per_page', 15);
            $perPage = min(max($perPage, 1), 100);
            $paginator = $query->paginate($perPage);

            $data = $paginator->getCollection()->map(function ($item) {
                $fileUrl = $item->file;
                if ($fileUrl && !str_starts_with($fileUrl, 'http')) {
                    $fileUrl = asset($fileUrl);
                }
                $thumbUrl = $item->thumbnail;
                if ($thumbUrl && !str_starts_with($thumbUrl, 'http')) {
                    $thumbUrl = asset($thumbUrl);
                }
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'judul' => $item->judul,
                    'type' => $item->type,
                    'file' => $fileUrl,
                    'thumbnail' => $thumbUrl,
                    'sort_order' => $item->sort_order,
                    'created_at' => $item->created_at?->toIso8601String(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
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
     * GET /api/v1/tutorial-videos/{id}
     */
    public function show(Request $request, int|string $id): JsonResponse
    {
        $id = (int) $id;
        try {
            $item = TutorialVideo::find($id);
            if (!$item) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Tutorial video tidak ditemukan.',
                ], 404);
            }
            $fileUrl = $item->file;
            if ($fileUrl && !str_starts_with($fileUrl, 'http')) {
                $fileUrl = asset($fileUrl);
            }
            $thumbUrl = $item->thumbnail;
            if ($thumbUrl && !str_starts_with($thumbUrl, 'http')) {
                $thumbUrl = asset($thumbUrl);
            }
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $item->id,
                    'title' => $item->title,
                    'judul' => $item->judul,
                    'file' => $fileUrl,
                    'thumbnail' => $thumbUrl,
                    'sort_order' => $item->sort_order,
                    'created_at' => $item->created_at?->toIso8601String(),
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

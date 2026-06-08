<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Concerns\FormatsPhotoUrl;
use App\Models\DigitalLearningSupport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DigitalLearningSupportApiController extends Controller
{
    use FormatsPhotoUrl;

    /**
     * GET /api/v1/digital-learning-supports
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        $query = DigitalLearningSupport::with('user')->orderBy('id', 'desc');

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
                'title' => $item->title,
                'judul' => $item->judul,
                'video_tipe' => $item->video_tipe,
                'file' => $item->video_tipe === 'internal' ? $this->formatPhoto($item->file) : null,
                'embed' => $item->video_tipe === 'external' ? $item->embed : null,
                'created_by' => $item->created_by,
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
     * GET /api/v1/digital-learning-supports/{id}
     */
    public function show(int $id): JsonResponse
    {
        $item = DigitalLearningSupport::with('user')->find($id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $item->id,
                'title' => $item->title,
                'judul' => $item->judul,
                'video_tipe' => $item->video_tipe,
                'file' => $item->video_tipe === 'internal' ? $this->formatPhoto($item->file) : null,
                'embed' => $item->video_tipe === 'external' ? $item->embed : null,
                'created_by' => $item->created_by,
                'created_at' => $item->created_at?->toIso8601String(),
            ],
        ]);
    }
}

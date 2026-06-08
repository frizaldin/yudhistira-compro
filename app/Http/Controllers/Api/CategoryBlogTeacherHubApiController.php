<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Concerns\FormatsPhotoUrl;
use App\Models\CategoryTeacherHub;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryBlogTeacherHubApiController extends Controller
{
    use FormatsPhotoUrl;

    /**
     * GET /api/v1/category-teacher-hubs
     */
    public function index(Request $request): JsonResponse
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
}

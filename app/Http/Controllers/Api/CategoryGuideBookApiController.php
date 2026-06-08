<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CategoryGuideBook;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryGuideBookApiController extends Controller
{
    /**
     * GET /api/v1/category-guide-books
     */
    public function index(Request $request): JsonResponse
    {
        $query = CategoryGuideBook::where('visible', 'yes')->orderBy('order');

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

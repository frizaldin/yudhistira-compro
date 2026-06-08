<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Concerns\FormatsPhotoUrl;
use App\Models\TeacherReward;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TeacherRewardApiController extends Controller
{
    use FormatsPhotoUrl;

    /**
     * GET /api/v1/teacher-rewards
     */
    public function index(Request $request): JsonResponse
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

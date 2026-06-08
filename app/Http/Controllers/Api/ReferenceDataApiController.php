<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\City;
use App\Models\MataPelajaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReferenceDataApiController extends Controller
{
    /**
     * GET /api/v1/provinces
     */
    public function provinces(Request $request): JsonResponse
    {
        $items = Province::orderBy('name')->get(['code', 'name']);

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * GET /api/v1/cities
     * Query: province_code (optional) - filter by province
     */
    public function cities(Request $request): JsonResponse
    {
        $query = City::orderBy('name');

        if ($request->filled('province_code')) {
            $query->where('province_code', $request->get('province_code'));
        }

        $items = $query->get(['code', 'province_code', 'name']);

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }

    /**
     * GET /api/v1/mata-pelajaran
     */
    public function mataPelajaran(Request $request): JsonResponse
    {
        $items = MataPelajaran::whereNull('deleted_at')->orderBy('title')->get(['id', 'title']);

        return response()->json([
            'success' => true,
            'data' => $items,
        ]);
    }
}

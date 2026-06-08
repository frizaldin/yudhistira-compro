<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CreativeTeacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreativeTeacherApiController extends Controller
{
    /**
     * GET /api/v1/creative-teachers
     * Daftar hasil kerja milik guru yang login.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $teacher = Auth::guard('api')->user();
            if (!$teacher) {
                return response()->json([
                    'code' => 401,
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $query = CreativeTeacher::where('user_id', $teacher->id);

            if ($request->filled('status')) {
                $status = $request->query('status');
                if (in_array($status, ['new', 'under review', 'rejected', 'accepted'], true)) {
                    $query->where('status', $status);
                }
            }

            if ($request->filled('search')) {
                $search = '%' . $request->query('search') . '%';
                $query->where(function ($q) use ($search) {
                    $q->where('number', 'like', $search)
                        ->orWhere('title', 'like', $search)
                        ->orWhere('topic', 'like', $search)
                        ->orWhere('message', 'like', $search);
                });
            }

            $perPage = (int) $request->query('per_page', 15);
            $perPage = min(max($perPage, 1), 100);

            $items = $query->orderBy('datetime', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $items->items(),
                'meta' => [
                    'current_page' => $items->currentPage(),
                    'last_page' => $items->lastPage(),
                    'per_page' => $items->perPage(),
                    'total' => $items->total(),
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
     * GET /api/v1/creative-teachers/{id}
     * Detail satu item (hanya milik guru yang login).
     */
    public function show(Request $request, int|string $id): JsonResponse
    {
        $id = (int) $id;
        try {
            $teacher = Auth::guard('api')->user();
            if (!$teacher) {
                return response()->json([
                    'code' => 401,
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $item = CreativeTeacher::where('user_id', $teacher->id)->find($id);
            if (!$item) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Data tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $item,
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
     * POST /api/v1/creative-teachers/create
     * Upload hasil kerja baru.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $teacher = Auth::guard('api')->user();
            if (!$teacher) {
                return response()->json([
                    'code' => 401,
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $request->validate([
                'title' => 'required|string|max:255',
                'topic' => 'nullable|string|max:255',
                'message' => 'nullable|string',
                'link_upload' => 'nullable|string|max:500',
            ]);

            $number = CreativeTeacher::generateNumber();
            $datetime = now();

            $item = CreativeTeacher::create([
                'user_id' => $teacher->id,
                'number' => $number,
                'title' => $request->input('title'),
                'topic' => $request->input('topic'),
                'message' => $request->input('message'),
                'link_upload' => $request->input('link_upload'),
                'status' => 'new',
                'datetime' => $datetime,
            ]);

            return response()->json([
                'code' => 201,
                'success' => true,
                'message' => 'Hasil kerja berhasil diupload.',
                'data' => $item,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'code' => 422,
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * PUT /api/v1/creative-teachers/update/{id}
     * Update hasil kerja (hanya milik guru, hanya jika status masih new/rejected).
     */
    public function update(Request $request, int|string $id): JsonResponse
    {
        $id = (int) $id;
        try {
            $teacher = Auth::guard('api')->user();
            if (!$teacher) {
                return response()->json([
                    'code' => 401,
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $item = CreativeTeacher::where('user_id', $teacher->id)->find($id);
            if (!$item) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Data tidak ditemukan.',
                ], 404);
            }

            if (!in_array($item->status, ['new', 'rejected'], true)) {
                return response()->json([
                    'code' => 403,
                    'success' => false,
                    'message' => 'Data sedang dalam review atau sudah diterima, tidak dapat diedit.',
                ], 403);
            }

            $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'topic' => 'nullable|string|max:255',
                'message' => 'nullable|string',
                'link_upload' => 'nullable|string|max:500',
            ]);

            $item->fill($request->only(['title', 'topic', 'message', 'link_upload']));
            $item->save();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate.',
                'data' => $item,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'code' => 422,
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * DELETE /api/v1/creative-teachers/delete/{id}
     * Hapus hasil kerja (hanya milik guru, hanya jika status new/rejected).
     */
    public function destroy(Request $request, int|string $id): JsonResponse
    {
        $id = (int) $id;
        try {
            $teacher = Auth::guard('api')->user();
            if (!$teacher) {
                return response()->json([
                    'code' => 401,
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $item = CreativeTeacher::where('user_id', $teacher->id)->find($id);
            if (!$item) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Data tidak ditemukan.',
                ], 404);
            }

            if (!in_array($item->status, ['new', 'rejected'], true)) {
                return response()->json([
                    'code' => 403,
                    'success' => false,
                    'message' => 'Data sedang dalam review atau sudah diterima, tidak dapat dihapus.',
                ], 403);
            }

            $item->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.',
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

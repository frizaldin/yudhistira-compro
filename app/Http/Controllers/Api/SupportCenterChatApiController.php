<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportCenter;
use App\Models\SupportCenterChat;
use App\Models\SupportCenterChatAttachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class SupportCenterChatApiController extends Controller
{
    /**
     * GET /api/v1/support-centers/chats?ticket_id=1
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

            $ticketId = $request->ticket_id;

            if (!$ticketId) {
                return response()->json([
                    'code' => 422,
                    'success' => false,
                    'message' => 'Parameter ticket_id wajib diisi.',
                ], 422);
            }
            $ticket = SupportCenter::where('user_id', $teacher->id)->find($ticketId);
            if (!$ticket) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Data tidak ditemukan.',
                ], 404);
            }

            $chats = SupportCenterChat::where('support_center_id', $ticketId)
                ->with('attachments')
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $chats,
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
     * POST /api/v1/support-centers/chats
     * Kirim chat baru (dari user/guru). Body: ticket_id, message, files (optional).
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
                'ticket_id' => 'required|integer|exists:support_centers,id',
                'message' => 'required|string',
                'files' => 'nullable|array',
                'files.*' => 'file|max:10240',
            ]);

            $ticketId = (int) $request->input('ticket_id');
            $ticket = SupportCenter::where('user_id', $teacher->id)->find($ticketId);
            if (!$ticket) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Tiket tidak ditemukan.',
                ], 404);
            }

            $chat = DB::transaction(function () use ($request, $ticketId, $teacher) {
                $chat = SupportCenterChat::create([
                    'support_center_id' => $ticketId,
                    'user_id' => $teacher->id,
                    'viewpoint' => 'user',
                    'message' => $request->input('message'),
                    'is_read' => 'no',
                    'datetime' => now(),
                ]);

                $uploadedFiles = $request->file('files');
                if ($uploadedFiles) {
                    $files = is_array($uploadedFiles) ? $uploadedFiles : [$uploadedFiles];
                    $folder = 'support_centers/chats';
                    foreach ($files as $file) {
                        $filesize = $file->getSize(); // ambil size sebelum upload (file dipindah)
                        $path = $this->uploadNormal($file, $folder);
                        SupportCenterChatAttachment::create([
                            'support_center_chat_id' => $chat->id,
                            'file' => $path,
                            'datetime' => now(),
                            'filesize' => $filesize,
                        ]);
                    }
                }

                return $chat->load('attachments');
            });

            return response()->json([
                'code' => 201,
                'success' => true,
                'message' => 'Chat berhasil dikirim.',
                'data' => $chat,
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
     * PUT /api/v1/support-centers/chats/{chatId}
     * Update chat (edit message atau mark as read). Hanya chat milik user.
     */
    public function update(Request $request, int|string $chatId): JsonResponse
    {
        $chatId = (int) $chatId;
        try {
            $teacher = Auth::guard('api')->user();
            if (!$teacher) {
                return response()->json([
                    'code' => 401,
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $chat = SupportCenterChat::where('user_id', $teacher->id)
                ->where('viewpoint', 'user')
                ->with('supportCenter', 'attachments')
                ->find($chatId);

            if (!$chat) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Chat tidak ditemukan.',
                ], 404);
            }

            $updates = [];

            if ($request->has('message')) {
                $request->validate(['message' => 'required|string']);
                $updates['message'] = $request->input('message');
            }

            if ($request->has('is_read')) {
                $request->validate(['is_read' => 'required|in:yes,no']);
                $updates['is_read'] = $request->input('is_read');
            }

            if (empty($updates)) {
                return response()->json([
                    'code' => 422,
                    'success' => false,
                    'message' => 'Tidak ada data yang diupdate. Kirim message atau is_read.',
                ], 422);
            }

            DB::transaction(function () use ($chat, $updates) {
                $chat->update($updates);
            });

            return response()->json([
                'success' => true,
                'message' => 'Chat berhasil diupdate.',
                'data' => $chat->fresh('attachments'),
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
     * DELETE /api/v1/support-centers/chats/{chatId}
     * Hapus chat (hanya milik user/guru).
     */
    public function destroy(Request $request, int|string $chatId): JsonResponse
    {
        $chatId = (int) $chatId;
        try {
            $teacher = Auth::guard('api')->user();
            if (!$teacher) {
                return response()->json([
                    'code' => 401,
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $chat = SupportCenterChat::where('user_id', $teacher->id)
                ->where('viewpoint', 'user')
                ->with('attachments', 'supportCenter')
                ->find($chatId);

            if (!$chat) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Chat tidak ditemukan.',
                ], 404);
            }

            $attachments = $chat->attachments;

            DB::transaction(function () use ($chat) {
                $chat->delete();
            });

            foreach ($attachments as $att) {
                $fullPath = public_path($att->file);
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Chat berhasil dihapus.',
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

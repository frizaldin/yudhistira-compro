<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OpenTicket;
use App\Models\OpenTicketAttachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class OpenTicketApiController extends Controller
{
    /**
     * GET /api/v1/open-tickets
     * Daftar tiket milik guru yang login.
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

            $query = OpenTicket::where('user_id', $teacher->id);

            if ($request->filled('status')) {
                $status = $request->query('status');
                if (in_array($status, ['new', 'process', 'done'], true)) {
                    $query->where('status', $status);
                }
            }

            if ($request->filled('search')) {
                $search = '%' . $request->query('search') . '%';
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', $search)
                        ->orWhere('topic', 'like', $search)
                        ->orWhere('message', 'like', $search);
                });
            }

            $items = $query->with('attachments')
                ->orderBy('datetime', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $items,
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
     * GET /api/v1/open-tickets/{id}
     * Detail satu tiket (hanya milik guru yang login).
     */
    public function show(Request $request, int $id): JsonResponse
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

            $ticket = OpenTicket::where('user_id', $teacher->id)->with('attachments')->find($id);
            if (!$ticket) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Tiket tidak ditemukan.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $ticket,
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
     * POST /api/v1/open-tickets/create
     * Buka tiket baru (pertanyaan atau hal lainnya).
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
                'message' => 'required|string',
                'files' => 'nullable|array',
                'files.*' => 'file|max:10240', // max 10MB per file
            ]);

            $ticketNumber = OpenTicket::generateTicketNumber();
            $datetime = now();

            $ticket = OpenTicket::create([
                'user_id' => $teacher->id,
                'ticket_number' => $ticketNumber,
                'status' => 'new',
                'title' => $request->input('title'),
                'topic' => $request->input('topic'),
                'message' => $request->input('message'),
                'datetime' => $datetime,
            ]);

            $uploadedFiles = $request->file('files');
            if ($uploadedFiles) {
                $files = is_array($uploadedFiles) ? $uploadedFiles : [$uploadedFiles];
                $folder = 'open_tickets/';
                foreach ($files as $file) {
                    try {
                        $path = $this->uploadNormal($file, $folder);
                        OpenTicketAttachment::create([
                            'open_ticket_id' => $ticket->id,
                            'path' => $path,
                            'original_name' => $file->getClientOriginalName(),
                        ]);
                    } catch (\Throwable $e) {
                        return response()->json([
                            'code' => 422,
                            'success' => false,
                            'message' => 'Gagal upload file: ' . $e->getMessage(),
                        ], 422);
                    }
                }
            }

            $ticket->load('attachments');

            return response()->json([
                'code' => 201,
                'success' => true,
                'message' => 'Tiket berhasil dibuat.',
                'data' => $ticket,
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
     * PUT /api/v1/open-tickets/update/{id}
     * Update tiket (hanya milik guru yang login).
     */
    public function update(Request $request, int $id): JsonResponse
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

            $ticket = OpenTicket::where('user_id', $teacher->id)->find($id);
            if (!$ticket) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Tiket tidak ditemukan.',
                ], 404);
            }

            $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'topic' => 'nullable|string|max:255',
                'message' => 'sometimes|required|string',
                'status' => 'sometimes|in:new,process,done',
            ]);

            $ticket->fill($request->only(['title', 'topic', 'message', 'status']));
            $ticket->save();

            $ticket->load('attachments');

            return response()->json([
                'success' => true,
                'message' => 'Tiket berhasil diupdate.',
                'data' => $ticket,
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
     * DELETE /api/v1/open-tickets/delete/{id}
     * Hapus tiket milik guru yang login (beserta lampiran dan file di disk).
     */
    public function destroy(Request $request, int $id): JsonResponse
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

            $ticket = OpenTicket::where('user_id', $teacher->id)->with('attachments')->find($id);
            if (!$ticket) {
                return response()->json([
                    'code' => 404,
                    'success' => false,
                    'message' => 'Tiket tidak ditemukan.',
                ], 404);
            }

            $uploadDir = public_path('storage/upload/open_tickets/' . $ticket->id);
            if (File::isDirectory($uploadDir)) {
                File::deleteDirectory($uploadDir);
            }

            $ticket->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tiket berhasil dihapus.',
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

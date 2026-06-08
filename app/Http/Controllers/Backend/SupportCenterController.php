<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SupportCenter;
use App\Models\SupportCenterChat;
use App\Models\SupportCenterChatAttachment;
use App\Models\UserPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SupportCenterController extends Controller
{
    /**
     * Daftar semua tiket (Open Tiket / Support Center).
     */
    public function index(Request $request)
    {
        $query = SupportCenter::with(['teacher', 'attachments'])
            ->orderBy('datetime', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('status') && in_array($request->status, ['new', 'process', 'done'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', $search)
                    ->orWhere('title', 'like', $search)
                    ->orWhere('topic', 'like', $search)
                    ->orWhere('message', 'like', $search)
                    ->orWhereHas('teacher', function ($t) use ($search) {
                        $t->where('name', 'like', $search)->orWhere('email', 'like', $search);
                    });
            });
        }

        $data['collection'] = $query->paginate(15)->withQueryString();

        return view('backend.support_center.index', $data);
    }

    /**
     * Detail tiket + chat.
     */
    public function show($id)
    {
        $data['item'] = SupportCenter::with(['teacher', 'attachments', 'chats.attachments'])
            ->findOrFail($id);

        return view('backend.support_center.show', $data);
    }

    /**
     * Admin balas chat tiket.
     */
    public function storeChat(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'files.*' => 'nullable|file|max:10240',
        ]);

        $ticket = SupportCenter::findOrFail($id);

        DB::transaction(function () use ($request, $ticket, $id) {
            $chat = SupportCenterChat::create([
                'support_center_id' => $id,
                'user_id' => null,
                'viewpoint' => 'admin',
                'message' => $request->input('message'),
                'is_read' => 'no',
                'datetime' => now(),
            ]);

            $uploadedFiles = $request->file('files');
            if ($uploadedFiles) {
                $files = is_array($uploadedFiles) ? $uploadedFiles : [$uploadedFiles];
                $folder = 'support_centers/chats';
                foreach ($files as $file) {
                    $filesize = $file->getSize();
                    $path = $this->uploadNormal($file, $folder);
                    SupportCenterChatAttachment::create([
                        'support_center_chat_id' => $chat->id,
                        'file' => $path,
                        'datetime' => now(),
                        'filesize' => $filesize,
                    ]);
                }
            }

            if ($ticket->status === 'new') {
                $ticket->update(['status' => 'process']);
            }
        });

        return redirect()->back()->with('success', 'Balasan berhasil dikirim.');
    }

    /**
     * Admin update chat (hanya chat admin).
     */
    public function updateChat(Request $request, $chatId)
    {
        $request->validate([
            'message' => 'required|string',
            'files.*' => 'nullable|file|max:10240',
        ]);

        $chat = SupportCenterChat::with('supportCenter', 'attachments')
            ->where('viewpoint', 'admin')
            ->findOrFail($chatId);

        $ticketId = $chat->support_center_id;

        DB::transaction(function () use ($request, $chat, $ticketId) {
            $chat->update([
                'message' => $request->input('message'),
            ]);

            $uploadedFiles = $request->file('files');
            if ($uploadedFiles) {
                $files = is_array($uploadedFiles) ? $uploadedFiles : [$uploadedFiles];
                $folder = 'support_centers/' . $ticketId . '/chats';
                foreach ($files as $file) {
                    $filesize = $file->getSize();
                    $path = $this->upload($file, $folder);
                    SupportCenterChatAttachment::create([
                        'support_center_chat_id' => $chat->id,
                        'file' => $path,
                        'datetime' => now(),
                        'filesize' => $filesize,
                    ]);
                }
            }
        });

        return redirect()->back()->with('success', 'Chat berhasil diupdate.');
    }

    /**
     * Admin update status tiket (new, process, done).
     * Jika status = done dan point > 0, beri point ke user dengan catatan (note).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,process,done',
            'point' => 'nullable|integer|min:0',
            'note' => 'nullable|string|max:1000',
        ]);

        $ticket = SupportCenter::findOrFail($id);
        $ticket->update(['status' => $request->input('status')]);

        $point = (int) $request->input('point', 0);
        $note = $request->input('note');
        if ($ticket->status === 'done' && $point > 0) {
            UserPoint::create([
                'user_id' => $ticket->user_id,
                'type'    => 'IN',
                'point'   => $point,
                'note'    => $note ?: 'Support Center #' . $ticket->ticket_number,
                'source'  => 'support_center',
                'origin'  => [
                    'id'            => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Status berhasil diupdate.' . ($point > 0 ? " Point {$point} telah diberikan ke user." : ''));
    }

    /**
     * Admin hapus chat (hanya chat admin).
     */
    public function destroyChat($chatId)
    {
        $chat = SupportCenterChat::with('attachments')
            ->where('viewpoint', 'admin')
            ->findOrFail($chatId);

        foreach ($chat->attachments as $att) {
            $fullPath = public_path($att->file);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }

        $chat->delete();

        return redirect()->back()->with('success', 'Chat berhasil dihapus.');
    }
}

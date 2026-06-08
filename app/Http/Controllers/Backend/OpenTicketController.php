<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OpenTicket;
use App\Models\OpenTicketChat;
use App\Models\OpenTicketChatAttachment;
use App\Models\UserPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class OpenTicketController extends Controller
{
    /**
     * Daftar semua tiket (Open Ticket).
     */
    public function index(Request $request)
    {
        $query = OpenTicket::with(['teacher', 'attachments'])
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

        return view('backend.open_ticket.index', $data);
    }

    /**
     * Detail tiket + chat.
     */
    public function show($id)
    {
        $data['item'] = OpenTicket::with(['teacher', 'attachments', 'chats.attachments'])
            ->findOrFail($id);

        return view('backend.open_ticket.show', $data);
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

        $ticket = OpenTicket::findOrFail($id);

        DB::transaction(function () use ($request, $ticket, $id) {
            $chat = OpenTicketChat::create([
                'open_ticket_id' => $id,
                'user_id' => null,
                'viewpoint' => 'admin',
                'message' => $request->input('message'),
                'is_read' => 'no',
                'datetime' => now(),
            ]);

            $uploadedFiles = $request->file('files');
            if ($uploadedFiles) {
                $files = is_array($uploadedFiles) ? $uploadedFiles : [$uploadedFiles];
                $folder = 'open_tickets/chats';
                foreach ($files as $file) {
                    $filesize = $file->getSize();
                    $path = $this->uploadNormal($file, $folder);
                    OpenTicketChatAttachment::create([
                        'open_ticket_chat_id' => $chat->id,
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

        $chat = OpenTicketChat::with('openTicket', 'attachments')
            ->where('viewpoint', 'admin')
            ->findOrFail($chatId);

        $ticketId = $chat->open_ticket_id;

        DB::transaction(function () use ($request, $chat, $ticketId) {
            $chat->update([
                'message' => $request->input('message'),
            ]);

            $uploadedFiles = $request->file('files');
            if ($uploadedFiles) {
                $files = is_array($uploadedFiles) ? $uploadedFiles : [$uploadedFiles];
                $folder = 'open_tickets/' . $ticketId . '/chats';
                foreach ($files as $file) {
                    $filesize = $file->getSize();
                    $path = $this->uploadNormal($file, $folder);
                    OpenTicketChatAttachment::create([
                        'open_ticket_chat_id' => $chat->id,
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

        $ticket = OpenTicket::findOrFail($id);
        $ticket->update(['status' => $request->input('status')]);

        $point = (int) $request->input('point', 0);
        $note = $request->input('note');
        if ($ticket->status === 'done' && $point > 0) {
            UserPoint::create([
                'user_id' => $ticket->user_id,
                'type'    => 'IN',
                'point'   => $point,
                'note'    => $note ?: 'Open Ticket #' . $ticket->ticket_number,
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
        $chat = OpenTicketChat::with('attachments')
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

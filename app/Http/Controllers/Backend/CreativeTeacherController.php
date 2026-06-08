<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CreativeTeacher;
use App\Models\UserPoint;
use Illuminate\Http\Request;

class CreativeTeacherController extends Controller
{
    /**
     * Daftar semua hasil kerja guru (Creative Teacher).
     */
    public function index(Request $request)
    {
        $query = CreativeTeacher::with('teacher')
            ->orderBy('datetime', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('status') && in_array($request->status, ['new', 'under review', 'rejected', 'accepted'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', $search)
                    ->orWhere('title', 'like', $search)
                    ->orWhere('topic', 'like', $search)
                    ->orWhere('message', 'like', $search)
                    ->orWhereHas('teacher', function ($t) use ($search) {
                        $t->where('name', 'like', $search)->orWhere('email', 'like', $search);
                    });
            });
        }

        $data['collection'] = $query->paginate(15)->withQueryString();

        return view('backend.creative_teacher.index', $data);
    }

    /**
     * Detail hasil kerja + form update status.
     */
    public function show($id)
    {
        $data['item'] = CreativeTeacher::with('teacher')->findOrFail($id);

        return view('backend.creative_teacher.show', $data);
    }

    /**
     * Admin update status (under review, rejected, accepted).
     * Jika status = accepted dan point > 0, beri point ke user dengan catatan (note).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,under review,rejected,accepted',
            'point' => 'nullable|integer|min:0',
            'note' => 'nullable|string|max:1000',
        ]);

        $item = CreativeTeacher::findOrFail($id);
        $item->update(['status' => $request->input('status')]);

        $point = (int) $request->input('point', 0);
        $note = $request->input('note');
        if ($item->status === 'accepted' && $point > 0) {
            UserPoint::create([
                'user_id' => $item->user_id,
                'type'    => 'IN',
                'point'   => $point,
                'note'    => $note ?: 'Creative Teacher #' . $item->number,
                'source'  => 'creative_teacher',
                'origin'  => [
                    'id'     => $item->id,
                    'number' => $item->number,
                    'title'  => $item->title,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Status berhasil diupdate.' . ($point > 0 ? " Point {$point} telah diberikan ke user." : ''));
    }
}

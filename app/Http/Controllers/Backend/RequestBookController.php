<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RequestBook;
use Illuminate\Http\Request;

class RequestBookController extends Controller
{
    public function index(Request $request)
    {
        $query = RequestBook::with(['teacher', 'details'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('status') && in_array($request->status, ['pending', 'processed', 'rejected'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('number', 'like', $search)
                    ->orWhere('code_book', 'like', $search)
                    ->orWhereHas('teacher', function ($t) use ($search) {
                        $t->where('name', 'like', $search)->orWhere('email', 'like', $search);
                    });
            });
        }

        $data['collection'] = $query->paginate(15)->withQueryString();

        return view('backend.request_book.index', $data);
    }

    public function show($id)
    {
        $data['item'] = RequestBook::with(['teacher', 'details'])
            ->findOrFail($id);

        return view('backend.request_book.show', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processed,rejected',
            'code_book' => 'required|file|mimes:pdf'
        ]);

        $item = RequestBook::findOrFail($id);
        
        if($request->input('status') == 'processed' || $item->input('status') == 'processed') {
            $code_book = $this->uploadNormal($request->file('code_book'), 'code_book');
            $item->code_book = $code_book;
        }
        
        $item->note = $request->input('note');
        
        if($request->input('status')) {
            $item->status = $request->input('status');
        }
        $item->save();

        return redirect()
            ->to(url('backend/request-books/' . $id))
            ->with('success', 'Permintaan buku berhasil diperbarui.');
    }
}

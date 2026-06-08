<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\GuideBook;
use App\Models\CategoryGuideBook;
use Illuminate\Http\Request;

class GuideBookController extends Controller
{
    public $title, $base_url;

    public function __construct()
    {
        $this->title = 'Buku Panduan';
        $this->base_url = url('backend/guide-books');
    }

    public function index()
    {
        $data['collection'] = GuideBook::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('judul', 'like', '%' . $search . '%');
        })->with('category')->orderBy('id', 'desc')->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.guide_book.index', $data);
    }

    public function add()
    {
        $data['categories'] = CategoryGuideBook::orderBy('order')->get();
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.guide_book.add', $data);
    }

    public function store(Request $req)
    {
        try {
            // return $req;
            $req->validate([
                'category_id' => 'required',
                'title'       => 'required|string',
                'judul'       => 'nullable|string',
                'thumbnail'   => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:2048',
                'file_pdf'    => 'nullable|file|mimes:pdf,PDF|max:10240',
            ]);
    
            GuideBook::create([
                'category_id' => $req->category_id,
                'thumbnail'   => $req->hasFile('thumbnail') ? $this->uploadNormal($req->file('thumbnail'), 'guide-book') : null,
                'title'       => $req->title,
                'judul'       => $req->judul ?? '',
                'file'        => $req->hasFile('file_pdf') ? $this->uploadNormal($req->file('file_pdf'), 'guide-book-pdf') : null,
            ]);
    
            return [
                'code'    => 200,
                'success' => true,
                'url'     => $this->base_url,
            ];
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $firstMessage = collect($errors)->flatten()->first();
            
            return response()->json([
                'code'    => 422,
                'success' => false,
                'errors'  => $errors,
                'message' => $firstMessage
            ], 422);
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = GuideBook::find($id);
        $data['categories'] = CategoryGuideBook::orderBy('order')->get();
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.guide_book.edit', $data);
    }

    public function update(Request $req)
    {
        try {
            $req->validate([
                'category_id' => 'required',
                'title'       => 'required|string',
                'judul'       => 'nullable|string',
                'thumbnail'   => 'nullable|file|mimes:jpg,jpeg,png,webp,gif|max:2048',
                'file'        => 'nullable|file|mimes:pdf|max:10240',
            ]);
            
            $data = GuideBook::find($req->id);
            $data->update([
                'category_id' => $req->category_id ?? $data->category_id,
                'thumbnail' => $req->thumbnail ? $this->uploadNormal($req->thumbnail, 'guide-book') : $data->thumbnail,
                'title' => $req->title,
                'judul' => $req->judul ?? $data->judul,
                'file' => $req->file('file') ? $this->uploadNormal($req->file('file'), 'guide-book-pdf') : $data->file,
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => $this->base_url,
            ];
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'code'    => 422,
                'success' => false,
                'errors'  => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function delete(Request $req)
    {
        try {
            $data = GuideBook::find($req->id);
            $data->delete();
            return [
                'code' => 200,
                'success' => true,
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}

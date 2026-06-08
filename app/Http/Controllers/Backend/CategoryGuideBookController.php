<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CategoryGuideBook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryGuideBookController extends Controller
{
    public $title, $base_url;

    public function __construct()
    {
        $this->title = 'Kategori Buku Panduan';
        $this->base_url = url('backend/category-guide-books');
    }

    public function index()
    {
        $data['collection'] = CategoryGuideBook::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('judul', 'like', '%' . $search . '%');
        })->orderBy('order', 'ASC')->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_guide_book.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_guide_book.add', $data);
    }

    public function store(Request $req)
    {
        try {
            CategoryGuideBook::create([
                'title' => $req->title,
                'judul' => $req->judul ?? '',
                'order' => $req->order ?? 0,
                'url' => $req->url ?: Str::slug($req->title ?? $req->judul),
                'visible' => $req->visible ? 'yes' : 'no',
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => $this->base_url,
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = CategoryGuideBook::find($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_guide_book.edit', $data);
    }

    public function update(Request $req)
    {
        try {
            $data = CategoryGuideBook::find($req->id);
            $data->update([
                'title' => $req->title,
                'judul' => $req->judul ?? $data->judul,
                'order' => $req->order ?? $data->order,
                'url' => $req->url ?: Str::slug($req->title ?? $req->judul),
                'visible' => $req->visible ? 'yes' : 'no',
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => $this->base_url,
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function delete(Request $req)
    {
        try {
            $data = CategoryGuideBook::find($req->id);
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

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CategoryBlog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryBlogController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Kategori Blog';
        $this->base_url = url('backend/category-blog');
    }
    public function index()
    {
        $data['collection'] = CategoryBlog::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('judul', 'like', '%' . $search . '%');
        })->orderBy('order', 'ASC')->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_blog.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_blog.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = CategoryBlog::create([
                'title' => $req->title,
                'judul' => $req->judul,
                'order' => $req->order ?? 0,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'category-blog') : null,
                'visible' => $req->visible ? 'yes' : 'no',
                'url' => Str::slug($req->title ?? $req->judul),
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => $this->base_url
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = CategoryBlog::find($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_blog.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = CategoryBlog::find($req->id);
            $data->update([
                'title' => $req->title,
                'judul' => $req->judul,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'category-blog') : $data->file,
                'order' => $req->order ?? $data->order,
                'url' => Str::slug($req->title ?? $req->judul),
                'visible' => $req->visible ? 'yes' : 'no'
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => $this->base_url
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = CategoryBlog::find($req->id);
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

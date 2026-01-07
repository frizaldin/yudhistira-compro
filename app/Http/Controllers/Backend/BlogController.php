<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\CategoryBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {

        $data['collection'] = Blog::when(request('search'), function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('nama', 'like', '%' . $search . '%');
        })->when(Auth::user()->authorities_id != 1, function ($query, $search) {
            return $query->where('created_by',  Auth::user()->id);
        })->paginate(10);
        return view('backend.blog.index', $data);
    }

    public function add()
    {
        $data['category'] = CategoryBlog::where('visible', 'yes')->get();
        return view('backend.blog.add', $data);
    }

    function store(Request $req)
    {
        try {
            $validVisible = in_array($req->visible, ['draft', 'pending', 'publish']) ? $req->visible : 'draft';
            $create = Blog::create([
                'name' => $req->name,
                'nama' => $req->nama ?? '',
                'category_id' => $req->category_id,
                'description' => $req->description,
                'deskripsi' => $req->deskripsi ?? '',
                'overview' => $req->overview,
                'pratinjau' => $req->pratinjau ?? '',
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'Blog') : '',
                'tags' => $req->tags,
                'meta_description' => $req->meta_description,
                'date' => !empty($req->date) ? $req->date : null,
                'meta_keyword' => $req->meta_keyword,
                'url' => Str::slug($req->name),
                'visible' => $validVisible,
                'created_by' => Auth::user()->id
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/blog')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = Blog::find($id);
        // return $data['item'];
        $data['category'] = CategoryBlog::where('visible', 'yes')->get();
        return view('backend.blog.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Blog::find($req->id);
            $validVisible = in_array($req->visible, ['draft', 'pending', 'publish']) ? $req->visible : ($data->visible ?? 'draft');
            $data->update([
                'name' => $req->name,
                'nama' => $req->nama ?? $data->nama,
                'category_id' => $req->category_id,
                'description' => $req->description,
                'deskripsi' => $req->deskripsi ?? $data->deskripsi,
                'overview' => $req->overview,
                'pratinjau' => $req->pratinjau ?? $data->pratinjau,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'Blog') : $data->photo,
                'tags' => $req->tags,
                'meta_description' => $req->meta_description,
                'meta_keyword' => $req->meta_keyword,
                'date' => !empty($req->date) ? $req->date : $data->date,
                'url' => Str::slug($req->name ?? $req->nama),
                'visible' => $validVisible
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/blog')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = Blog::find($req->id);
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

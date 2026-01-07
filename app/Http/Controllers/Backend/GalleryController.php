<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Gallery';
        $this->base_url = url('backend/gallery');
    }
    public function index()
    {
        $data['collection'] = Gallery::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.gallery.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['categories'] = Category::where('type', 'gallery')->get();
        return view('backend.gallery.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Gallery::create([
                'title' => $req->title,
                'category_id' => $req->category_id,
                'type' => $req->type,
                'file' => ($req->type == 'photo') ? $this->uploadNormal($req->photo, 'file') : $req->video,
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

    public function edit($id)
    {
        $data['item'] = Gallery::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['categories'] = Category::where('type', 'gallery')->get();
        $data['base_url'] = $this->base_url;
        return view('backend.gallery.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Gallery::find($req->id);
            $data->update([
                'title' => $req->title,
                'category_id' => $req->category_id,
                'type' => $req->type,
                'file' => ($req->type == 'photo') ? $this->uploadNormal($req->photo, 'file') : $req->video,
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
            $data = Gallery::find($req->id);
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

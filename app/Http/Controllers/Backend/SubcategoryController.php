<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubcategoryController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Seri';
        $this->base_url = url('backend/subcategory');
    }
    public function index()
    {
        $data['collection'] = Subcategory::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('judul', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.subcategory.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['categories'] = Category::where('visible', 'yes')->where('type', 'product')->get();
        return view('backend.subcategory.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Subcategory::create([
                'title' => $req->title,
                'judul' => $req->judul,
                'category_id' => $req->category_id ?? null,
                'description' => $req->description,
                'deskripsi' => $req->deskripsi,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'Photo') : '',
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
        $data['item'] = Subcategory::find($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['categories'] = Category::where('visible', 'yes')->where('type', 'product')->get();
        return view('backend.subcategory.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Subcategory::find($req->id);
            $data->update([
                'title' => $req->title,
                'judul' => $req->judul,
                'category_id' => $req->category_id ?? $data->category_id,
                'description' => $req->description,
                'deskripsi' => $req->deskripsi,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'Photo') : $data->photo,
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

    function delete(Request $req)
    {
        try {
            $data = Subcategory::find($req->id);
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

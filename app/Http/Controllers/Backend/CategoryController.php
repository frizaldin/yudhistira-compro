<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Kategori';
        $this->base_url = url('backend/category');
    }
    public function index()
    {
        $data['collection'] = Category::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('judul', 'like', '%' . $search . '%');
        })->orderBy('type', 'ASC')->orderBy('order', 'ASC')->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['services'] = Service::where('visible', 'yes')->get();
        return view('backend.category.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Category::create([
                'title' => $req->title,
                'judul' => $req->judul,
                'type' => $req->type,
                'service_id' => $req->service_id ?? null,
                'order' => $req->order,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : '',
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
        $data['item'] = Category::find($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['services'] = Service::where('visible', 'yes')->get();
        return view('backend.category.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Category::find($req->id);
            $data->update([
                'title' => $req->title,
                'judul' => $req->judul,
                'type' => $req->type,
                'service_id' => $req->service_id ?? $data->service_id,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : $data->file,
                'order' => $req->order,
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
            $data = Category::find($req->id);
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

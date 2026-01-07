<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CategoryEvent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryEventController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Kategori Event';
        $this->base_url = url('backend/category-event');
    }
    public function index()
    {
        $data['collection'] = CategoryEvent::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('judul', 'like', '%' . $search . '%');
        })->orderBy('order', 'ASC')->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_event.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_event.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = CategoryEvent::create([
                'title' => $req->title,
                'judul' => $req->judul,
                'order' => $req->order ?? 0,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'category-event') : null,
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
        $data['item'] = CategoryEvent::find($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_event.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = CategoryEvent::find($req->id);
            $data->update([
                'title' => $req->title,
                'judul' => $req->judul,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'category-event') : $data->file,
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
            $data = CategoryEvent::find($req->id);
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

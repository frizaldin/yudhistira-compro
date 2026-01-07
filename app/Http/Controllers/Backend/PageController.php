<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Halaman';
        $this->base_url = url('backend/page');
    }
    public function index()
    {
        $data['collection'] = Page::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.page.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['category'] = Page::all();
        return view('backend.page.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Page::create([
                'title' => $req->title,
                'description' => $req->description,
                'link' => Str::slug($req->title)
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
        $data['item'] = Page::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.page.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Page::find($req->id);
            $data->update([
                'title' => $req->title,
                'description' => $req->description,
                'link' => Str::slug($req->title)
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
            $data = Page::find($req->id);
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

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Menu';
        $this->base_url = url('backend/menu');
    }
    public function index()
    {
        $data['collection'] = Menu::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.menu.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['page'] = Page::all();
        return view('backend.menu.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Menu::create([
                'title' => $req->title,
                'type' => $req->type,
                'link' => $req->link,
                'target' => $req->target,
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
        $data['item'] = Menu::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['page'] = Page::all();
        return view('backend.menu.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Menu::find($req->id);
            $data->update([
                'title' => $req->title,
                'type' => $req->type,
                'link' => $req->link,
                'target' => $req->target,
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
            $data = Menu::find($req->id);
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

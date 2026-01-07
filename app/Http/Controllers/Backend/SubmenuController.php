<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Submenu;
use Illuminate\Http\Request;

class SubmenuController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Sub Menu';
        $this->base_url = url('backend/submenu');
    }
    public function index()
    {
        $data['collection'] = Submenu::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.submenu.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['menu'] = Menu::get();
        $data['page'] = Page::all();
        return view('backend.submenu.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Submenu::create([
                'menu_id' => $req->menu_id,
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
        $data['item'] = Submenu::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['page'] = Page::get();
        $data['menu'] = Menu::all();
        return view('backend.submenu.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Submenu::find($req->id);
            $data->update([
                'menu_id' => $req->menu_id,
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
            $data = Submenu::find($req->id);
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

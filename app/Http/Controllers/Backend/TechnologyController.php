<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Technology;
use Illuminate\Http\Request;

class TechnologyController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Teknologi';
        $this->base_url = url('backend/technology');
    }
    public function index()
    {
        $data['collection'] = Technology::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.technology.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['category'] = Technology::all();
        return view('backend.technology.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Technology::create([
                'title' => $req->title,
                'description' => $req->description,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : '',
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
        $data['item'] = Technology::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.technology.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Technology::find($req->id);
            $data->update([
                'title' => $req->title,
                'description' => $req->description,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : $data->file,
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
            $data = Technology::find($req->id);
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

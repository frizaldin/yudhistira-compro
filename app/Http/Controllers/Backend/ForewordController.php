<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Foreword;
use Illuminate\Http\Request;

class ForewordController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Keunggulan';
        $this->base_url = url('backend/foreword');
    }
    public function index()
    {
        $data['collection'] = Foreword::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.foreword.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['category'] = Foreword::all();
        return view('backend.foreword.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Foreword::create([
                'title' => $req->title,
                'overview' => $req->overview,
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
        $data['item'] = Foreword::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.foreword.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Foreword::find($req->id);
            $data->update([
                'title' => $req->title,
                'description' => $req->description,
                'overview' => $req->overview,
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
            $data = Foreword::find($req->id);
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

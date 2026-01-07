<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Superiority;
use Illuminate\Http\Request;

class SuperiorityController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Keunggulan';
        $this->base_url = url('backend/superiority');
    }
    public function index()
    {
        $data['collection'] = Superiority::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.superiority.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['category'] = Superiority::all();
        return view('backend.superiority.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Superiority::create([
                'title' => $req->title,
                'description' => $req->description,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : '',
                'file2' => ($req->file2) ? $this->uploadNormal($req->file2, 'file2') : '',
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
        $data['item'] = Superiority::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.superiority.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Superiority::find($req->id);
            $data->update([
                'title' => $req->title,
                'description' => $req->description,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : $data->file,
                'file2' => ($req->file2) ? $this->uploadNormal($req->file2, 'file2') : $data->file2,
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
            $data = Superiority::find($req->id);
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

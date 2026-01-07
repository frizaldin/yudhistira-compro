<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Subsidiary;
use Illuminate\Http\Request;

class SubsidiaryController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Anak Perusahaan';
        $this->base_url = url('backend/subsidiary');
    }
    public function index()
    {
        $data['collection'] = Subsidiary::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.subsidiary.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['category'] = Subsidiary::all();
        return view('backend.subsidiary.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Subsidiary::create([
                'title' => $req->title,
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
        $data['item'] = Subsidiary::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.subsidiary.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Subsidiary::find($req->id);
            $data->update([
                'title' => $req->title,
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
            $data = Subsidiary::find($req->id);
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

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Authority;
use App\Models\Feature;
use Illuminate\Http\Request;

class AuthorityController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Otoritas';
        $this->base_url = url('backend/authority');
    }

    function index()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['collection'] = Authority::paginate(10);
        return view('backend.authority.index', $data);
    }

    function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['features'] = Feature::whereNotIn('id', [20,17,10,19,13,15])->orderBy('title')->get();
        return view('backend.authority.add', $data);
    }

    function edit($id)
    {
        $data['item'] = Authority::find($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['features'] = Feature::whereNotIn('id', [20,17,10,19,13,15])->orderBy('title')->get();
        return view('backend.authority.edit', $data);
    }

    function store(Request $req)
    {
        try {
            Authority::create([
                'title' => $req->title,
                'code' => json_encode($req->code),
            ]);
            return [
                'code' => 201,
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
            $data = Authority::find($req->id);
            $data->delete();
            return [
                'code' => 200,
                'success' => true,
            ];
        } catch (\Throwable $th) {
            $message = ($th->errorInfo[1] === 1451) ? 'Otoritas sudah terdaftar di user admin lain' : $th->getMessage();
            return errors($th, $message);
        }
    }

    function update(Request $req)
    {
        try {
            $find = Authority::find($req->id);
            $find->update([
                'title' => $req->title,
                'code' => json_encode($req->code),
            ]);
            return [
                'code' => 201,
                'success' => true,
                'url' => $this->base_url
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}

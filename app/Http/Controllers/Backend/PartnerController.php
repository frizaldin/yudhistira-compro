<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Klien Logo';
        $this->base_url = url('backend/partner');
    }
    public function index()
    {
        $data['collection'] = Partner::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.partner.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['category'] = Partner::all();
        return view('backend.partner.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Partner::create([
                'link' => $req->link,
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
        $data['item'] = Partner::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.partner.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Partner::find($req->id);
            $data->update([
                'link' => $req->link,
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
            $data = Partner::find($req->id);
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

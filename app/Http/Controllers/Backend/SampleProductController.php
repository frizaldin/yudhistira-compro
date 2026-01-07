<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SampleProduct;
use Illuminate\Http\Request;

class SampleProductController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Sample Product';
        $this->base_url = url('backend/sample-product');
    }
    public function index()
    {
        $data['collection'] = SampleProduct::when(request('search'), function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('nama', 'like', '%' . $search . '%');
        })->orderBy('created_at', 'DESC')->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.sample_product.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.sample_product.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = SampleProduct::create([
                'name' => $req->name,
                'nama' => $req->nama,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'sample-product') : null,
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
        $data['item'] = SampleProduct::find($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.sample_product.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = SampleProduct::find($req->id);
            $data->update([
                'name' => $req->name,
                'nama' => $req->nama,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'sample-product') : $data->file,
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
            $data = SampleProduct::find($req->id);
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

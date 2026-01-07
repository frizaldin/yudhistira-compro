<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DigitalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DigitalProductController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Digital Product';
        $this->base_url = url('backend/digital-product');
    }
    public function index()
    {
        $data['collection'] = DigitalProduct::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('judul', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.digital-product.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.digital-product.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = DigitalProduct::create([
                'logo' => ($req->logo) ? $this->uploadNormal($req->logo, 'DigitalProduct') : '',
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'DigitalProduct') : '',
                'title' => $req->title,
                'judul' => $req->judul,
                'description' => $req->description,
                'deskripsi' => $req->deskripsi,
                'button_text_1' => $req->button_text_1,
                'tombol_teks_1' => $req->tombol_teks_1,
                'button_link_1' => $req->button_link_1,
                'button_text_2' => $req->button_text_2,
                'button_teks_2' => $req->button_teks_2,
                'button_link_2' => $req->button_link_2,
                'iframe_youtube' => $req->iframe_youtube,
                'url' => Str::slug($req->title),
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
        $data['item'] = DigitalProduct::find($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.digital-product.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = DigitalProduct::find($req->id);
            $data->update([
                'logo' => ($req->logo) ? $this->uploadNormal($req->logo, 'DigitalProduct') : $data->logo,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'DigitalProduct') : $data->photo,
                'title' => $req->title,
                'judul' => $req->judul,
                'description' => $req->description,
                'deskripsi' => $req->deskripsi,
                'button_text_1' => $req->button_text_1,
                'tombol_teks_1' => $req->tombol_teks_1,
                'button_link_1' => $req->button_link_1,
                'button_text_2' => $req->button_text_2,
                'button_teks_2' => $req->button_teks_2,
                'button_link_2' => $req->button_link_2,
                'iframe_youtube' => $req->iframe_youtube,
                'url' => Str::slug($req->title),
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
            $data = DigitalProduct::find($req->id);
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

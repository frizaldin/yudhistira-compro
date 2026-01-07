<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Layanan';
        $this->base_url = url('backend/service');
    }
    public function index()
    {
        $data['collection'] = Service::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.service.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['category'] = Category::where('visible', 'yes')->where('type', 'service')->get();
        return view('backend.service.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Service::create([
                'title' => $req->title,
                'judul' => $req->judul,
                'description' => $req->description,
                'deskripsi' => $req->deskripsi,
                'detail_information' => $req->detail_information,
                'detail_informasi' => $req->detail_informasi,
                'meta_keyword' => $req->meta_keyword,
                'meta_description' => $req->meta_description,
                'meta_tag' => $req->meta_tag,
                'button_text' => $req->button_text,
                'button_link' => $req->button_link,
                'order' => $req->order,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : '',
                'bg_potrait' => ($req->bg_potrait) ? $this->uploadNormal($req->bg_potrait, 'file') : '',
                'bg_landscape' => ($req->bg_landscape) ? $this->uploadNormal($req->bg_landscape, 'file') : '',
                'pdf' => ($req->pdf) ? $this->uploadNormal($req->pdf, 'file') : '',
                'visible' => $req->visible ? 'yes' : 'no',
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
        $data['item'] = Service::find($id);
        $data['category'] = Category::where('visible', 'yes')->where('type', 'service')->get();
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.service.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Service::find($req->id);
            $data->update([
                'title' => $req->title,
                'judul' => $req->judul,
                'description' => $req->description,
                'deskripsi' => $req->deskripsi,
                'button_text' => $req->button_text,
                'button_link' => $req->button_link,
                'order' => $req->order,
                'detail_information' => $req->detail_information,
                'detail_informasi' => $req->detail_informasi,
                'meta_keyword' => $req->meta_keyword,
                'meta_description' => $req->meta_description,
                'meta_tag' => $req->meta_tag,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : $data->file,
                'bg_potrait' => ($req->bg_potrait) ? $this->uploadNormal($req->bg_potrait, 'file') : $data->bg_potrait,
                'bg_landscape' => ($req->bg_landscape) ? $this->uploadNormal($req->bg_landscape, 'file') : $data->bg_landscape,
                'pdf' => ($req->pdf) ? $this->uploadNormal($req->pdf, 'file') : $data->pdf,
                'visible' => $req->visible ? 'yes' : 'no',
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
            $data = Service::find($req->id);
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

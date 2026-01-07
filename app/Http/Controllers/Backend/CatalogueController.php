<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use App\Models\CategoryCatalogue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CatalogueController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Catalogue';
        $this->base_url = url('backend/catalogue');
    }
    public function index()
    {
        $data['collection'] = Catalogue::with('category')->when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.catalogue.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['categories'] = CategoryCatalogue::where('visible', 'yes')->orderBy('order', 'ASC')->get();
        return view('backend.catalogue.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Catalogue::create([
                'title' => $req->title,
                'judul' => $req->judul ?? null,
                'description' => $req->description ?? null,
                'category_id' => $req->category_id ?? null,
                'thumbnail' => ($req->thumbnail) ? $this->uploadNormal($req->thumbnail, 'catalogue') : null,
                'pdf' => ($req->pdf) ? $this->uploadNormal($req->pdf, 'catalogue') : null,
                'visible' => $req->visible ? 'yes' : 'no',
                'url' => Str::slug($req->title ?? $req->judul),
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
        $data['item'] = Catalogue::find($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['categories'] = CategoryCatalogue::where('visible', 'yes')->orderBy('order', 'ASC')->get();
        return view('backend.catalogue.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Catalogue::find($req->id);
            $data->update([
                'title' => $req->title,
                'judul' => $req->judul ?? $data->judul,
                'description' => $req->description ?? $data->description,
                'category_id' => $req->category_id ?? $data->category_id,
                'thumbnail' => ($req->thumbnail) ? $this->uploadNormal($req->thumbnail, 'catalogue') : $data->thumbnail,
                'pdf' => ($req->pdf) ? $this->uploadNormal($req->pdf, 'catalogue') : $data->pdf,
                'visible' => $req->visible ? 'yes' : 'no',
                'url' => $data->url ?? Str::slug($req->title ?? $req->judul),
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
            $data = Catalogue::find($req->id);
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

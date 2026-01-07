<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Portfolio;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Portofolio';
        $this->base_url = url('backend/portfolio');
    }
    public function index()
    {
        $data['collection'] = Portfolio::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->orderBy('order', 'ASC')->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.portfolio.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['category'] = Category::where('visible', 'yes')->where('type', 'portfolio')->get();

        return view('backend.portfolio.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Portfolio::create([
                'category_id' => $req->category_id,
                'title' => $req->title,
                'description' => $req->description,
                'order' => $req->order,
                'detail_information' => $req->detail_information,
                'meta_keyword' => $req->meta_keyword,
                'meta_description' => $req->meta_description,
                'meta_tag' => $req->meta_tag,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : '',
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
        $data['item'] = Portfolio::find($id);
        $data['category'] = Category::where('visible', 'yes')->where('type', 'portfolio')->get();
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.portfolio.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Portfolio::find($req->id);
            $data->update([
                'category_id' => $req->category_id,
                'title' => $req->title,
                'order' => $req->order,
                'description' => $req->description,
                'detail_information' => $req->detail_information,
                'meta_keyword' => $req->meta_keyword,
                'meta_description' => $req->meta_description,
                'meta_tag' => $req->meta_tag,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : $data->file,
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
            $data = Portfolio::find($req->id);
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

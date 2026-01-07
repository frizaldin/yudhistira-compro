<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Marketing;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Keunggulan';
        $this->base_url = url('backend/marketing');
    }
    public function index()
    {
        $data['collection'] = Marketing::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.marketing.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['category'] = Marketing::all();
        return view('backend.marketing.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Marketing::create([
                'name' => $req->name,
                'wa' => $req->wa,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'file') : '',
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
        $data['item'] = Marketing::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.marketing.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Marketing::find($req->id);
            $data->update([
                'name' => $req->name,
                'wa' => $req->wa,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'file') : $data->photo,
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
            $data = Marketing::find($req->id);
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

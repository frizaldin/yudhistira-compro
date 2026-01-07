<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Pricelist;
use Illuminate\Http\Request;

class PricelistController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Keunggulan';
        $this->base_url = url('backend/pricelist');
    }
    public function index()
    {
        $data['collection'] = Pricelist::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.pricelist.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['category'] = Pricelist::all();
        return view('backend.pricelist.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Pricelist::create([
                'title' => $req->title,
                'subtitle' => $req->subtitle,
                'description' => $req->description,
                'type' => $req->type,
                'price' => $req->price,
                'duration' => $req->duration,
                'benefit' => json_encode($req->benefit),
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
        $data['item'] = Pricelist::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.pricelist.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Pricelist::find($req->id);
            $data->update([
                'title' => $req->title,
                'subtitle' => $req->subtitle,
                'type' => $req->type,
                'description' => $req->description,
                'price' => $req->price,
                'duration' => $req->duration,
                'benefit' => json_encode($req->benefit),
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
            $data = Pricelist::find($req->id);
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

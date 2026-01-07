<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use Illuminate\Http\Request;

class CounterController extends Controller
{
    public function index()
    {
        $data['collection'] = Counter::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('judul', 'like', '%' . $search . '%');
        })->paginate(10);
        return view('backend.counter.index', $data);
    }

    public function add()
    {
        $data['category'] = Counter::all();
        return view('backend.counter.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Counter::create([
                'title' => $req->title,
                'judul' => $req->judul,
                'subtitle' => $req->subtitle,
                'subjudul' => $req->subjudul,
                'number' => priceToInt($req->number),
                'item' => $req->item,
                'satuan' => $req->satuan,
                'file' => ($req->photo) ? $this->uploadNormal($req->photo, 'Counter') : '',
                'visible' => $req->visible ? 'yes' : 'no'
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/counter')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = Counter::find($id);
        // return $data['item'];
        $data['category'] = Counter::all();
        return view('backend.counter.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Counter::find($req->id);
            $data->update([
                'title' => $req->title,
                'judul' => $req->judul,
                'subtitle' => $req->subtitle,
                'subjudul' => $req->subjudul,
                'number' => priceToInt($req->number),
                'item' => $req->item,
                'satuan' => $req->satuan,
                'file' => ($req->photo) ? $this->uploadNormal($req->photo, 'Photo') : $data->file,
                'visible' => $req->visible ? 'yes' : 'no'
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/counter')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = Counter::find($req->id);
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

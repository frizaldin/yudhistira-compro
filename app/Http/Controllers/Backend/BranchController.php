<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $data['collection'] = Branch::when(request('search'), function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%');
        })->orderBy('created_at', 'desc')->paginate(10);
        return view('backend.branch.index', $data);
    }

    public function add()
    {
        return view('backend.branch.add');
    }

    function store(Request $req)
    {
        try {
            $create = Branch::create([
                'name' => $req->name ?? '',
                'address' => $req->address ?? '',
                'latitude' => $req->latitude ?? '',
                'longitude' => $req->longitude ?? '',
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'Branch') : '',
                'visible' => $req->visible ? 'yes' : 'no'
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/branches')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = Branch::find($id);
        return view('backend.branch.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Branch::find($req->id);
            $data->update([
                'name' => $req->name ?? $data->name,
                'address' => $req->address ?? $data->address,
                'latitude' => $req->latitude ?? $data->latitude,
                'longitude' => $req->longitude ?? $data->longitude,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'Branch') : $data->photo,
                'visible' => $req->visible ? 'yes' : 'no'
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/branches')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = Branch::find($req->id);
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

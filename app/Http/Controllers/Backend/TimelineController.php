<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Timeline;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index()
    {
        $data['collection'] = Timeline::when(request('search'), function ($query, $search) {
            return $query->where('year', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhere('deskripsi', 'like', '%' . $search . '%');
        })->orderBy('year', 'desc')->paginate(10);
        return view('backend.timeline.index', $data);
    }

    public function add()
    {
        return view('backend.timeline.add');
    }

    function store(Request $req)
    {
        try {
            $create = Timeline::create([
                'year' => $req->year,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'Timeline') : '',
                'description' => $req->description ?? '',
                'deskripsi' => $req->deskripsi ?? '',
                'visible' => $req->visible ? 'yes' : 'no'
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/timeline')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = Timeline::find($id);
        return view('backend.timeline.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Timeline::find($req->id);
            $data->update([
                'year' => $req->year,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'Timeline') : $data->photo,
                'description' => $req->description ?? $data->description,
                'deskripsi' => $req->deskripsi ?? $data->deskripsi,
                'visible' => $req->visible ? 'yes' : 'no'
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/timeline')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = Timeline::find($req->id);
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

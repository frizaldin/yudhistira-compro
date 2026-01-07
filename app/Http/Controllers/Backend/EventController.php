<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CategoryEvent;
use Illuminate\Support\Str;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {

        $data['collection'] = Event::when(request('search'), function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->when(Auth::user()->authorities_id != 1, function ($query, $search) {
            return $query->where('created_by',  Auth::user()->id);
        })->paginate(10);
        return view('backend.event.index', $data);
    }

    public function add()
    {
        $data['category'] = CategoryEvent::where('visible', 'yes')->get();
        return view('backend.event.add', $data);
    }

    function store(Request $req)
    {
        try {
            $validVisible = in_array($req->visible, ['draft', 'pending', 'publish']) ? $req->visible : 'draft';
            $create = Event::create([
                'category_id' => $req->category_id ?? null,
                'name' => $req->name,
                'nama' => $req->nama ?? null,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'Event') : '',
                'overview' => $req->overview ?? '',
                'pratinjau' => $req->pratinjau ?? null,
                'description' => $req->description ?? '',
                'deskripsi' => $req->deskripsi ?? null,
                'tags' => $req->tags ?? '',
                'meta_keyword' => $req->meta_keyword ?? '',
                'meta_description' => $req->meta_description ?? '',
                'url' => Str::slug($req->name ?? $req->nama),
                'visible' => $validVisible,
                'date' => $req->date ?? null,
                'created_by' => Auth::user()->id
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/events')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = Event::find($id);
        // return $data['item'];
        $data['category'] = CategoryEvent::where('visible', 'yes')->get();
        return view('backend.event.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Event::find($req->id);
            $validVisible = in_array($req->visible, ['draft', 'pending', 'publish']) ? $req->visible : ($data->visible ?? 'draft');
            $data->update([
                'category_id' => $req->category_id ?? $data->category_id,
                'name' => $req->name,
                'nama' => $req->nama ?? $data->nama,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'Event') : $data->photo,
                'overview' => $req->overview ?? $data->overview,
                'pratinjau' => $req->pratinjau ?? $data->pratinjau,
                'description' => $req->description ?? $data->description,
                'deskripsi' => $req->deskripsi ?? $data->deskripsi,
                'tags' => $req->tags ?? $data->tags,
                'meta_keyword' => $req->meta_keyword ?? $data->meta_keyword,
                'meta_description' => $req->meta_description ?? $data->meta_description,
                'url' => Str::slug($req->name ?? $req->nama ?? $data->name),
                'visible' => $validVisible,
                'date' => $req->date ?? $data->date
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/events')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = Event::find($req->id);
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

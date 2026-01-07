<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index()
    {

        $data['collection'] = Experience::when(request('search'), function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->when(auth()->user()->authorities_id != 1, function ($query, $search) {
            return $query->where('created_by',  auth()->user()->id);
        })->paginate(10);
        return view('backend.experiences.index', $data);
    }

    public function add()
    {
        return view('backend.experiences.add');
    }

    function store(Request $req)
    {
        try {
            $create = Experience::create([
                'name' => $req->name,
                'description' => $req->description,
                'overview' => $req->overview,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'experience') : '',
                'tags' => $req->tags,
                'meta_description' => $req->meta_description,
                'date' => $req->date,
                'meta_keyword' => $req->meta_keyword,
                'url' => Str::slug($req->name),
                'visible' => $req->visible ? 'yes' : 'no',
                'created_by' => auth()->user()->id
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/experience')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = Experience::find($id);
        // return $data['item'];
        return view('backend.experiences.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Experience::find($req->id);
            $data->update([
                'name' => $req->name,
                'description' => $req->description,
                'overview' => $req->overview,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'experience') : $data->photo,
                'tags' => $req->tags,
                'meta_description' => $req->meta_description,
                'meta_keyword' => $req->meta_keyword,
                'date' => $req->date,
                'url' => Str::slug($req->name),
                'visible' => $req->visible ? 'yes' : 'no'
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/experience')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = Experience::find($req->id);
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

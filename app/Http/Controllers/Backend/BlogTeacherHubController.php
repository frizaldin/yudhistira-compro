<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BlogTeacherHub;
use App\Models\CategoryTeacherHub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogTeacherHubController extends Controller
{
    public function index()
    {
        $data['collection'] = BlogTeacherHub::when(request('search'), function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('nama', 'like', '%' . $search . '%');
        })->when(Auth::user()->authorities_id != 1, function ($query) {
            return $query->where('created_by', Auth::user()->id);
        })->paginate(10);
        return view('backend.blog_teacher_hub.index', $data);
    }

    public function add()
    {
        $data['category'] = CategoryTeacherHub::where('visible', 'yes')->get();
        return view('backend.blog_teacher_hub.add', $data);
    }

    function store(Request $req)
    {
        try {
            BlogTeacherHub::create([
                'name' => $req->name,
                'nama' => $req->nama ?? '',
                'category_id' => $req->category_id,
                'description' => $req->description ?? '',
                'deskripsi' => $req->deskripsi ?? '',
                'overview' => $req->overview ?? '',
                'pratinjau' => $req->pratinjau ?? '',
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'BlogTeacherHub') : '',
                'tags' => $req->tags ?? '',
                'date' => !empty($req->date) ? $req->date : null,
                'url' => Str::slug($req->name ?? $req->nama),
                'visible' => $req->visible === 'yes' ? 'yes' : 'no',
                'created_by' => Auth::user()->id
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/blog-teacher-hubs')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = BlogTeacherHub::find($id);
        $data['category'] = CategoryTeacherHub::where('visible', 'yes')->get();
        return view('backend.blog_teacher_hub.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = BlogTeacherHub::find($req->id);
            $data->update([
                'name' => $req->name,
                'nama' => $req->nama ?? $data->nama,
                'category_id' => $req->category_id,
                'description' => $req->description ?? $data->description,
                'deskripsi' => $req->deskripsi ?? $data->deskripsi,
                'overview' => $req->overview ?? $data->overview,
                'pratinjau' => $req->pratinjau ?? $data->pratinjau,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'BlogTeacherHub') : $data->photo,
                'tags' => $req->tags ?? $data->tags,
                'date' => !empty($req->date) ? $req->date : $data->date,
                'url' => Str::slug($req->name ?? $req->nama ?? $data->name),
                'visible' => $req->visible === 'yes' ? 'yes' : 'no'
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/blog-teacher-hubs')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = BlogTeacherHub::find($req->id);
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

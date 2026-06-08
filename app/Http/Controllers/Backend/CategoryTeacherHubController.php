<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CategoryTeacherHub;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryTeacherHubController extends Controller
{
    public $title, $base_url;

    public function __construct()
    {
        $this->title = 'Kategori Artikel Guru';
        $this->base_url = url('backend/category-teacher-hubs');
    }

    public function index()
    {
        $data['collection'] = CategoryTeacherHub::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('judul', 'like', '%' . $search . '%');
        })->orderBy('order', 'ASC')->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_teacher_hub.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_teacher_hub.add', $data);
    }

    function store(Request $req)
    {
        try {
            CategoryTeacherHub::create([
                'title' => $req->title,
                'judul' => $req->judul,
                'order' => $req->order ?? 0,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'category-teacher-hub') : null,
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
        $data['item'] = CategoryTeacherHub::find($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_teacher_hub.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = CategoryTeacherHub::find($req->id);
            $data->update([
                'title' => $req->title,
                'judul' => $req->judul,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'category-teacher-hub') : $data->file,
                'order' => $req->order ?? $data->order,
                'url' => Str::slug($req->title ?? $req->judul),
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
            $data = CategoryTeacherHub::find($req->id);
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

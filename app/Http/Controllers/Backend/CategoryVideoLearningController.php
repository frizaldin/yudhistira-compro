<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\VideoLearningCategory;
use Illuminate\Http\Request;

class CategoryVideoLearningController extends Controller
{
    public $title, $base_url;

    public function __construct()
    {
        $this->title = 'Kategori Video Learning';
        $this->base_url = url('backend/category-video-learnings');
    }

    public function index()
    {
        $data['collection'] = VideoLearningCategory::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->orderBy('id')->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_video_learning.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_video_learning.add', $data);
    }

    public function store(Request $req)
    {
        try {
            VideoLearningCategory::create([
                'title' => $req->title,
            ]);
            return ['code' => 200, 'success' => true, 'url' => $this->base_url];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = VideoLearningCategory::findOrFail($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.category_video_learning.edit', $data);
    }

    public function update(Request $req)
    {
        try {
            $data = VideoLearningCategory::findOrFail($req->id);
            $data->update(['title' => $req->title]);
            return ['code' => 200, 'success' => true, 'url' => $this->base_url];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function delete(Request $req)
    {
        try {
            VideoLearningCategory::findOrFail($req->id)->delete();
            return ['code' => 200, 'success' => true];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}

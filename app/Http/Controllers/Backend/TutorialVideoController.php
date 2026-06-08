<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TutorialVideo;
use Illuminate\Http\Request;

class TutorialVideoController extends Controller
{
    public $title, $base_url;

    public function __construct()
    {
        $this->title = 'Tutorial Video';
        $this->base_url = url('backend/tutorial-videos');
    }

    public function index()
    {
        $data['collection'] = TutorialVideo::when(request('search'), function ($q, $search) {
            return $q->where('title', 'like', '%' . $search . '%')->orWhere('judul', 'like', '%' . $search . '%');
        })->orderBy('sort_order')->orderBy('id', 'desc')->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.tutorial_video.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.tutorial_video.add', $data);
    }

    public function store(Request $req)
    {
        try {
            $req->validate([
                'file' => 'required|file|mimes:mp4,mkv,webm,avi,mov,flv|max:204800',
            ]);
            TutorialVideo::create([
                'title' => $req->title,
                'judul' => $req->judul,
                'file' => $this->uploadVideo($req->file('file'), 'tutorial-video'),
                'thumbnail' => $req->file('thumbnail') ? $this->upload($req->file('thumbnail'), 'tutorial-video') : null,
                'sort_order' => (int) ($req->sort_order ?? 0),
            ]);
            return ['code' => 200, 'success' => true, 'url' => $this->base_url];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = TutorialVideo::findOrFail($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.tutorial_video.edit', $data);
    }

    public function update(Request $req)
    {
        try {
            $item = TutorialVideo::findOrFail($req->id);
            $data = [
                'title' => $req->title,
                'judul' => $req->judul,
                'sort_order' => (int) ($req->sort_order ?? 0),
            ];
            if ($req->hasFile('file')) {
                $req->validate(['file' => 'file|mimes:mp4,mkv,webm,avi,mov,flv|max:204800']);
                if ($item->file && file_exists(public_path($item->file))) {
                    @unlink(public_path($item->file));
                }
                $data['file'] = $this->uploadVideo($req->file('file'), 'tutorial-video');
            }
            if ($req->hasFile('thumbnail')) {
                if ($item->thumbnail && file_exists(public_path($item->thumbnail))) {
                    @unlink(public_path($item->thumbnail));
                }
                $data['thumbnail'] = $this->upload($req->file('thumbnail'), 'tutorial-video');
            }
            $item->update($data);
            return ['code' => 200, 'success' => true, 'url' => $this->base_url];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function delete(Request $req)
    {
        try {
            $item = TutorialVideo::findOrFail($req->id);
            if ($item->file && file_exists(public_path($item->file))) {
                @unlink(public_path($item->file));
            }
            if ($item->thumbnail && file_exists(public_path($item->thumbnail))) {
                @unlink(public_path($item->thumbnail));
            }
            $item->delete();
            return ['code' => 200, 'success' => true];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}

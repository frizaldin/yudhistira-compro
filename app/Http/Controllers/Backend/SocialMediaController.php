<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Sosial Media';
        $this->base_url = url('backend/social_media');
    }
    public function index()
    {
        $data['collection'] = SocialMedia::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.social_media.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['category'] = SocialMedia::all();
        return view('backend.social_media.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = SocialMedia::create([
                'title' => $req->title,
                'link' => $req->link,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'SocialMedia') : '',
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
        $data['item'] = SocialMedia::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.social_media.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = SocialMedia::find($req->id);
            $data->update([
                'title' => $req->title,
                'link' => $req->link,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'SocialMedia') : $data->file,
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
            $data = SocialMedia::find($req->id);
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

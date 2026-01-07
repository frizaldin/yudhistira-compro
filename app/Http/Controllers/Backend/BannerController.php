<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $data['item'] = Banner::firstOrFail();
        return view('backend.banner.index', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Banner::find($req->id);

            $data->update([
                'title' => $req->title,
                'subtitle' => $req->subtitle,
                'link_youtube' => $req->link_youtube,
                'description' => $req->description,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'Blog') : $data->file,
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/banner')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BannerSuperiority;
use Illuminate\Http\Request;

class BannerSuperiorityController extends Controller
{
    public function index()
    {
        $data['item'] = BannerSuperiority::firstOrFail();
        return view('backend.banner_superiority.index', $data);
    }

    function update(Request $req)
    {
        try {
            $data = BannerSuperiority::find($req->id);
            $data->update([
                'title' => $req->title,
                'description' => $req->description,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'Blog') : $data->file,
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/banner_superiority')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}

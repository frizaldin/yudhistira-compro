<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SecondBanner;
use Illuminate\Http\Request;

class SecondBannerController extends Controller
{
    public function index()
    {
        $data['item'] = SecondBanner::firstOrFail();
        return view('backend.second_banner.index', $data);
    }

    function update(Request $req)
    {
        try {
            $data = SecondBanner::find($req->id);

            $data->update([
                'title' => $req->title,
                'description' => $req->description,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'Banner') : $data->file,
                'icon' => ($req->icon) ? $this->uploadNormal($req->icon, 'Banner') : $data->icon,
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/second_banner')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}

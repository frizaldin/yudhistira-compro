<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Headline;
use Illuminate\Http\Request;

class HeadlineController extends Controller
{
    public function index()
    {
        $data['item'] = Headline::firstOrFail();
        return view('backend.headline.index', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Headline::find($req->id);
            $data->update([
                'title' => $req->title,
                'subtitle' => $req->subtitle,
                'description' => $req->description,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : $data->file,
                // 'bg' => ($req->bg) ? $this->uploadNormal($req->bg, 'file') : $data->bg,
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/headline')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}

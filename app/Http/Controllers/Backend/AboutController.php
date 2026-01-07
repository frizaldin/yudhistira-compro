<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $data['item'] = About::firstOrFail();
        return view('backend.about.index', $data);
    }

    function update(Request $req)
    {
        try {
            $data = About::find($req->id);
            $legality = [];
            foreach ($req->legality ?? [] as $key => $value) {
                $legality[] = ($value) ? $this->uploadNormal($value, 'Blog') : '';
            }
            $data->update([
                'title' => $req->title,
                'judul' => $req->judul,
                'overview' => $req->overview,
                'pratinjau' => $req->pratinjau,
                'description' => $req->description,
                'deskripsi' => $req->deskripsi,
                'link_youtube' => $req->link_youtube,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'Blog') : $data->file,
                'legality' => json_encode($legality) ?? [],
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/about')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}

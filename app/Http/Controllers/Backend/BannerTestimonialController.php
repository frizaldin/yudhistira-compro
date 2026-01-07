<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BannerTestimonial;
use Illuminate\Http\Request;

class BannerTestimonialController extends Controller
{
    public function index()
    {
        $data['item'] = BannerTestimonial::firstOrFail();
        return view('backend.banner_testimonial.index', $data);
    }

    function update(Request $req)
    {
        try {
            $data = BannerTestimonial::find($req->id);
            $data->update([
                'title' => $req->title,
                'description' => $req->description,
                'button_text' => $req->button_text,
                'button_link' => $req->button_link,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'Blog') : $data->file,
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/banner_testimonial')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}

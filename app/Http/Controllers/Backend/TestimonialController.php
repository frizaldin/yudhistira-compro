<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Testimonial';
        $this->base_url = url('backend/testimonial');
    }
    public function index()
    {
        $data['collection'] = Testimonial::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.testimonial.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['category'] = Testimonial::all();
        return view('backend.testimonial.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Testimonial::create([
                'name' => $req->name,
                'project' => $req->project,
                'description' => $req->description,
                'agency' => $req->agency,
                'job' => $req->job,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : '',
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

    public function edit($id)
    {
        $data['item'] = Testimonial::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.testimonial.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Testimonial::find($req->id);
            $data->update([
                'project' => $req->project,
                'name' => $req->name,
                'description' => $req->description,
                'agency' => $req->agency,
                'job' => $req->job,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : $data->file,
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
            $data = Testimonial::find($req->id);
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

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $data['collection'] = Slider::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('judul', 'like', '%' . $search . '%');
        })->orderBy('order', 'ASC')->paginate(10);
        return view('backend.slider.index', $data);
    }

    public function add()
    {
        $data['category'] = Slider::all();
        return view('backend.slider.add', $data);
    }

    function store(Request $req)
    {
        try {
            $createData = [
                'title' => $req->title,
                'judul' => $req->judul,
                'subtitle' => $req->subtitle,
                'order' => $req->order,
                'description' => $req->description,
                'button_link_1' => $req->button_link_1,
                'button_text_1' => $req->button_text_1,
                'tombol_teks_1' => $req->tombol_teks_1,
                'button_link_2' => $req->button_text_2,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'Photo') : '',
                'background' => ($req->background) ? $this->uploadNormal($req->background, 'Background') : '',
                'visible' => $req->visible ? 'yes' : 'no'
            ];

            $create = Slider::create($createData);

            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/slider')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = Slider::find($id);
        // return $data['item'];
        $data['category'] = Slider::all();
        return view('backend.slider.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Slider::find($req->id);

            $updateData = [
                'title' => $req->title,
                'judul' => $req->judul,
                'subtitle' => $req->subtitle,
                'description' => $req->description,
                'button_link_1' => $req->button_link_1,
                'button_text_1' => $req->button_text_1,
                'tombol_teks_1' => $req->tombol_teks_1,
                'button_link_2' => $req->button_link_2,
                'order' => $req->order,
                'button_text_2' => $req->button_text_2,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'Photo') : $data->photo,
                'background' => ($req->background) ? $this->uploadNormal($req->background, 'Background') : $data->background,
                'visible' => $req->visible ? 'yes' : 'no'
            ];

            $data->update($updateData);

            // Refresh to get updated data
            $data->refresh();

            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/slider')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = Slider::find($req->id);
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

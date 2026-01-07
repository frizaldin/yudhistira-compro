<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SliderSecond;
use Illuminate\Http\Request;

class SliderSecondController extends Controller
{
    public function index()
    {
        $data['collection'] = SliderSecond::paginate(10);
        return view('backend.slider_second.index', $data);
    }

    public function add()
    {
        $data['category'] = SliderSecond::all();
        return view('backend.slider_second.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = SliderSecond::create([
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : '',
                'visible' => $req->visible ? 'yes' : 'no'
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/slider_second')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = SliderSecond::find($id);
        // return $data['item'];
        $data['category'] = SliderSecond::all();
        return view('backend.slider_second.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = SliderSecond::find($req->id);
            $data->update([
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'file') : $data->file,
                'visible' => $req->visible ? 'yes' : 'no'
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/slider_second')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = SliderSecond::find($req->id);
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

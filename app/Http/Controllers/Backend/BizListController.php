<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BizList;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BizListController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'List Jasa';
        $this->base_url = url('backend/biz_list');
    }
    public function index()
    {
        $data['collection'] = BizList::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.biz_list.index', $data);
    }

    public function add()
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['category'] = BizList::all();
        return view('backend.biz_list.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = BizList::create([
                'main_title' => $req->main_title,
                'title_1' => $req->title_1,
                'description_1' => $req->description_1,
                'file_1' => ($req->file_1) ? $this->uploadNormal($req->file_1, 'file_1') : '',
                'title_2' => $req->title_2,
                'description_2' => $req->description_2,
                'file_2' => ($req->file_2) ? $this->uploadNormal($req->file_2, 'file_2') : '',
                'title_3' => $req->title_3,
                'description_3' => $req->description_3,
                'file_3' => ($req->file_3) ? $this->uploadNormal($req->file_3, 'file_3') : '',
                'url' => Str::slug($req->main_title),
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
        $data['item'] = BizList::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.biz_list.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = BizList::find($req->id);
            $data->update([
                'main_title' => $req->main_title,
                'title_1' => $req->title_1,
                'description_1' => $req->description_1,
                'file_1' => ($req->file_1) ? $this->uploadNormal($req->file_1, 'file_1') : $data->file_1,
                'title_2' => $req->title_2,
                'description_2' => $req->description_2,
                'file_2' => ($req->file_2) ? $this->uploadNormal($req->file_2, 'file_2') : $data->file_2,
                'title_3' => $req->title_3,
                'description_3' => $req->description_3,
                'file_3' => ($req->file_3) ? $this->uploadNormal($req->file_3, 'file_3') : $data->file_3,
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
            $data = BizList::find($req->id);
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

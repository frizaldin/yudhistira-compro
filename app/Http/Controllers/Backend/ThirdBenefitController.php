<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ThirdBenefit;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class ThirdBenefitController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Third Benefit';
        $this->base_url = url('backend/third-benefit');
    }
    public function index($relation_id = null)
    {
        $query = ThirdBenefit::with('subcategory')
            ->when($relation_id, function ($q) use ($relation_id) {
                return $q->where('relation_id', $relation_id);
            })
            ->when(request('search'), function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('judul', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('order', 'ASC');

        $data['collection'] = $query->paginate(10);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['subcategory_id'] = $relation_id;
        $data['subcategory'] = $relation_id ? Subcategory::find($relation_id) : null;
        return view('backend.third-benefit.index', $data);
    }

    public function add($relation_id = null)
    {
        $data['title'] = $this->title; 
        $data['base_url'] = $this->base_url;
        $data['relation_id'] = $relation_id;
        $data['subcategories'] = Subcategory::all();
        return view('backend.third-benefit.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = ThirdBenefit::create([
                'relation_id' => $req->relation_id ?? null,
                'title' => $req->title,
                'judul' => $req->judul ?? null,
                'description' => $req->description ?? '',
                'deskripsi' => $req->deskripsi ?? null,
                'order' => $req->order ?? 0,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'ThirdBenefit') : '',
                'visible' => $req->visible ? 'yes' : 'no'
            ]);

            $redirectUrl = $req->relation_id
                ? url('backend/third-benefit/' . $req->relation_id)
                : $this->base_url;

            return [
                'code' => 200,
                'success' => true,
                'url' => $redirectUrl
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = ThirdBenefit::with('subcategory')->find($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['digital_products'] = Subcategory::all();
        return view('backend.third-benefit.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = ThirdBenefit::find($req->id);
            $data->update([
                'relation_id' => $req->relation_id ?? $data->relation_id,
                'title' => $req->title,
                'judul' => $req->judul ?? $data->judul,
                'description' => $req->description ?? $data->description,
                'deskripsi' => $req->deskripsi ?? $data->deskripsi,
                'order' => $req->order ?? $data->order,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'ThirdBenefit') : $data->file,
                'visible' => $req->visible ? 'yes' : 'no'
            ]);

            $redirectUrl = $data->relation_id
                ? url('backend/third-benefit/' . $data->relation_id)
                : $this->base_url;

            return [
                'code' => 200,
                'success' => true,
                'url' => $redirectUrl
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = ThirdBenefit::find($req->id);
            $relation_id = $data->relation_id;
            $data->delete();

            $redirectUrl = $relation_id
                ? url('backend/third-benefit/' . $relation_id)
                : $this->base_url;

            return [
                'code' => 200,
                'success' => true,
                'url' => $redirectUrl
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}

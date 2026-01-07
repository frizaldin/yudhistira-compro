<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SecondBenefit;
use App\Models\DigitalProduct;
use Illuminate\Http\Request;

class SecondBenefitController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Second Benefit';
        $this->base_url = url('backend/second-benefit');
    }
    public function index($digital_product_id = null)
    {
        $query = SecondBenefit::with('digitalProduct')
            ->when($digital_product_id, function ($q) use ($digital_product_id) {
                return $q->where('digital_product_id', $digital_product_id);
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
        $data['digital_product_id'] = $digital_product_id;
        $data['digital_product'] = $digital_product_id ? DigitalProduct::find($digital_product_id) : null;
        return view('backend.second-benefit.index', $data);
    }

    public function add($digital_product_id = null)
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['digital_product_id'] = $digital_product_id;
        $data['digital_products'] = DigitalProduct::all();
        return view('backend.second-benefit.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = SecondBenefit::create([
                'digital_product_id' => $req->digital_product_id ?? null,
                'title' => $req->title,
                'judul' => $req->judul ?? null,
                'description' => $req->description ?? '',
                'deskripsi' => $req->deskripsi ?? null,
                'order' => $req->order ?? 0,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'SecondBenefit') : '',
                'visible' => $req->visible ? 'yes' : 'no'
            ]);

            $redirectUrl = $req->digital_product_id
                ? url('backend/second-benefit/' . $req->digital_product_id)
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
        $data['item'] = SecondBenefit::with('digitalProduct')->find($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['digital_products'] = DigitalProduct::all();
        return view('backend.second-benefit.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = SecondBenefit::find($req->id);
            $data->update([
                'digital_product_id' => $req->digital_product_id ?? $data->digital_product_id,
                'title' => $req->title,
                'judul' => $req->judul ?? $data->judul,
                'description' => $req->description ?? $data->description,
                'deskripsi' => $req->deskripsi ?? $data->deskripsi,
                'order' => $req->order ?? $data->order,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'SecondBenefit') : $data->file,
                'visible' => $req->visible ? 'yes' : 'no'
            ]);

            $redirectUrl = $data->digital_product_id
                ? url('backend/second-benefit/' . $data->digital_product_id)
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
            $data = SecondBenefit::find($req->id);
            $digital_product_id = $data->digital_product_id;
            $data->delete();

            $redirectUrl = $digital_product_id
                ? url('backend/second-benefit/' . $digital_product_id)
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

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Benefit;
use App\Models\Service;
use Illuminate\Http\Request;

class BenefitController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Benefit';
        $this->base_url = url('backend/benefit');
    }
    public function index($service_id = null)
    {
        $query = Benefit::with('service')
            ->when($service_id, function ($q) use ($service_id) {
                return $q->where('service_id', $service_id);
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
        $data['service_id'] = $service_id;
        $data['service'] = $service_id ? Service::find($service_id) : null;
        return view('backend.benefit.index', $data);
    }

    public function add($service_id = null)
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['service_id'] = $service_id;
        $data['services'] = Service::where('visible', 'yes')->get();
        return view('backend.benefit.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = Benefit::create([
                'service_id' => $req->service_id ?? null,
                'title' => $req->title,
                'judul' => $req->judul ?? null,
                'description' => $req->description ?? '',
                'deskripsi' => $req->deskripsi ?? null,
                'order' => $req->order ?? 0,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'Benefit') : '',
                'visible' => $req->visible ? 'yes' : 'no'
            ]);

            $redirectUrl = $req->service_id
                ? url('backend/benefit/' . $req->service_id)
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
        $data['item'] = Benefit::with('service')->find($id);
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['services'] = Service::where('visible', 'yes')->get();
        return view('backend.benefit.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = Benefit::find($req->id);
            $data->update([
                'service_id' => $req->service_id ?? $data->service_id,
                'title' => $req->title,
                'judul' => $req->judul ?? $data->judul,
                'description' => $req->description ?? $data->description,
                'deskripsi' => $req->deskripsi ?? $data->deskripsi,
                'order' => $req->order ?? $data->order,
                'file' => ($req->file) ? $this->uploadNormal($req->file, 'Benefit') : $data->file,
                'visible' => $req->visible ? 'yes' : 'no'
            ]);

            $redirectUrl = $data->service_id
                ? url('backend/benefit/' . $data->service_id)
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
            $data = Benefit::find($req->id);
            $service_id = $data->service_id;
            $data->delete();

            $redirectUrl = $service_id
                ? url('backend/benefit/' . $service_id)
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

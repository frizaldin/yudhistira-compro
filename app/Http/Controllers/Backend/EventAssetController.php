<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\EventAsset;
use Illuminate\Http\Request;

class EventAssetController extends Controller
{
    public $title, $base_url;
    public function __construct()
    {
        $this->title = 'Event Teaser';
        $this->base_url = url('backend/event_assets');
    }
    public function index($id)
    {
        $data['collection'] = EventAsset::where('event_id', $id)->when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);
        $data['title'] = $this->title;
        $data['event_id'] = $id;
        $data['base_url'] = $this->base_url;
        return view('backend.event_asset.index', $data);
    }

    public function add($id)
    {
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['category'] = EventAsset::all();
        $data['event_id'] = $id;
        return view('backend.event_asset.add', $data);
    }

    function store(Request $req)
    {
        try {
            $create = EventAsset::create([
                'event_id' => $req->event_id,
                'type' => $req->type,
                'file' => ($req->type == 'photo') ? $this->uploadNormal($req->photo, 'file') : $req->video,
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => $this->base_url . '/' . $req->event_id
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = EventAsset::find($id);
        // return $data['item'];
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        return view('backend.event_asset.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = EventAsset::find($req->id);
            $data->update([
                'type' => $req->type,
                'file' => ($req->type == 'photo') ? $this->uploadNormal($req->photo, 'file') : $req->video,
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => $this->base_url . '/' . $data->event_id
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = EventAsset::find($req->id);
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

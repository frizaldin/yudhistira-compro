<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CategoryEventTeacherHub;
use App\Models\EventTeacherHub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventTeacherHubController extends Controller
{
    public function index()
    {
        $data['collection'] = EventTeacherHub::with('category')->when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('judul', 'like', '%' . $search . '%');
        })->when(Auth::user()->authorities_id != 1, function ($query) {
            return $query->where('created_by', Auth::user()->id);
        })->orderBy('date', 'desc')->paginate(10);
        return view('backend.event_teacher_hub.index', $data);
    }

    public function add()
    {
        $data['category'] = CategoryEventTeacherHub::orderBy('order')->orderBy('title')->get();
        return view('backend.event_teacher_hub.add', $data);
    }

    function store(Request $req)
    {
        try {
            EventTeacherHub::create([
                'category_id' => $req->category_id ?: null,
                'title' => $req->title,
                'judul' => $req->judul ?? '',
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'EventTeacherHub') : '',
                'date' => !empty($req->date) ? $req->date : null,
                'start_time' => !empty($req->start_time) ? $req->start_time : null,
                'end_time' => !empty($req->end_time) ? $req->end_time : null,
                'point' => $req->point ?? '',
                'link_meeting' => $req->link_meeting ?? '',
                'created_by' => Auth::user()->id
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/event-teacher-hubs')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = EventTeacherHub::find($id);
        $data['category'] = CategoryEventTeacherHub::orderBy('order')->orderBy('title')->get();
        return view('backend.event_teacher_hub.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = EventTeacherHub::find($req->id);
            $data->update([
                'category_id' => $req->category_id ?: null,
                'title' => $req->title,
                'judul' => $req->judul ?? $data->judul,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'EventTeacherHub') : $data->photo,
                'date' => !empty($req->date) ? $req->date : $data->date,
                'start_time' => !empty($req->start_time) ? $req->start_time : $data->start_time,
                'end_time' => !empty($req->end_time) ? $req->end_time : $data->end_time,
                'point' => $req->point ?? $data->point,
                'link_meeting' => $req->link_meeting ?? $data->link_meeting
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/event-teacher-hubs')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = EventTeacherHub::find($req->id);
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

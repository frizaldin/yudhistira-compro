<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\EventTeacherHub;
use App\Models\EventTeacherHubQuestion;
use Illuminate\Http\Request;

class EventTeacherHubQuestionController extends Controller
{
    public $title, $base_url;

    public function __construct()
    {
        $this->title = 'Pertanyaan Event Guru';
        $this->base_url = url('backend/event-teacher-hub-questions');
    }

    public function index($event_id)
    {
        $event = EventTeacherHub::find($event_id);
        if (!$event) {
            return redirect(url('backend/event-teacher-hubs'))->with('error', 'Event tidak ditemukan.');
        }
        $data['event'] = $event;
        $data['collection'] = EventTeacherHubQuestion::where('event_id', $event_id)
            ->when(request('search'), function ($query, $search) {
                return $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('judul', 'like', '%' . $search . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        $data['title'] = $this->title;
        $data['event_id'] = $event_id;
        $data['base_url'] = $this->base_url;
        return view('backend.event_teacher_hub_question.index', $data);
    }

    public function add($event_id)
    {
        $event = EventTeacherHub::find($event_id);
        if (!$event) {
            return redirect(url('backend/event-teacher-hubs'))->with('error', 'Event tidak ditemukan.');
        }
        $data['event'] = $event;
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['event_id'] = $event_id;
        return view('backend.event_teacher_hub_question.add', $data);
    }

    public function store(Request $req)
    {
        try {
            EventTeacherHubQuestion::create([
                'event_id' => $req->event_id,
                'title' => $req->title,
                'judul' => $req->judul ?? '',
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => $this->base_url . '/' . $req->event_id,
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = EventTeacherHubQuestion::find($id);
        if (!$data['item']) {
            return redirect($this->base_url)->with('error', 'Data tidak ditemukan.');
        }
        $data['event'] = $data['item']->event;
        $data['title'] = $this->title;
        $data['base_url'] = $this->base_url;
        $data['event_id'] = $data['item']->event_id;
        return view('backend.event_teacher_hub_question.edit', $data);
    }

    public function update(Request $req)
    {
        try {
            $data = EventTeacherHubQuestion::find($req->id);
            $data->update([
                'title' => $req->title,
                'judul' => $req->judul ?? $data->judul,
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => $this->base_url . '/' . $data->event_id,
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function delete(Request $req)
    {
        try {
            $data = EventTeacherHubQuestion::find($req->id);
            $event_id = $data->event_id;
            $data->delete();
            return [
                'code' => 200,
                'success' => true,
                'url' => $this->base_url . '/' . $event_id,
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\TeacherReward;
use Illuminate\Http\Request;

class TeacherRewardController extends Controller
{
    public function index()
    {
        $data['collection'] = TeacherReward::when(request('search'), function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('judul', 'like', '%' . $search . '%');
        })->paginate(10);
        return view('backend.teacher_reward.index', $data);
    }

    public function add()
    {
        return view('backend.teacher_reward.add');
    }

    function store(Request $req)
    {
        try {
            TeacherReward::create([
                'title' => $req->title,
                'judul' => $req->judul ?? '',
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'TeacherReward') : '',
                'point' => $req->point ?? ''
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/teacher-rewards')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    public function edit($id)
    {
        $data['item'] = TeacherReward::find($id);
        return view('backend.teacher_reward.edit', $data);
    }

    function update(Request $req)
    {
        try {
            $data = TeacherReward::find($req->id);
            $data->update([
                'title' => $req->title,
                'judul' => $req->judul ?? $data->judul,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'TeacherReward') : $data->photo,
                'point' => $req->point ?? $data->point
            ]);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/teacher-rewards')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function delete(Request $req)
    {
        try {
            $data = TeacherReward::find($req->id);
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

<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Authority;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function index(Request $req)
    {
        return view('backend.user.index', [
            'title' => 'User',
            'collection' => User::whereNotIn('authorities_id', [2])->paginate(10)
        ]);
    }

    function add()
    {
        return view('backend.user.add', [
            'title' => 'User',
            'authority' => Authority::orderBy('title', 'asc')->get()
        ]);
    }

    function edit($id)
    {
        $find = User::where('id', $id)->first();
        if (!$find) {
            return redirect(url('backend/user'));
        }
        return view('backend.user.edit', [
            'title' => 'User',
            'authority' => Authority::orderBy('title', 'asc')->get(),
            'item' => $find
        ]);
    }

    function status(Request $req)
    {
        try {
            $find = User::find($req->id);
            $update = $find->update([
                'active' => $find->active == 'yes' ? 'no' : 'yes'
            ]);
            return [
                'code' => 201,
                'success' => true,
                'active' => $find->active == 'yes' ? 1 : 0
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function store(Request $req)
    {
        try {
            if ($req->password != $req->password_confirmation) {
                throw new \Exception('Password tidak cocok.');
            }
            User::create([
                'authorities_id' => $req->authorities_id,
                'name' => $req->nama,
                'email' => $req->email,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'User') : '',
                'password' => Hash::make($req->password),
                'active' => 1
            ]);
            return [
                'code' => 201,
                'success' => true,
                'url' => url('backend/user')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function update(Request $req)
    {
        try {
            $find = User::find($req->id);
            $find->update([
                'authorities_id' => $req->authorities_id,
                'photo' => ($req->photo) ? $this->uploadNormal($req->photo, 'User') : $find->photo,
                'name' => $req->nama,
                'email' => $req->email
            ]);
            if ($req->id == auth()->user()->id) {
                $ext = [
                    'message' => 'Profile berhasil diubah.'
                ];
            } else {
                $ext = [
                    'url' => url('backend/user')
                ];
            }
            return array_merge([
                'code' => 201,
                'success' => true,
            ], $ext);
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function password($id)
    {
        return view('backend.user.password', [
            'title' => 'Perusahaan',
            'item' => User::find($id)
        ]);
    }

    function changePassword(Request $req)
    {
        try {
            $data = User::find($req->id);
            if ($req->password != $req->password_confirmation) {
                throw new \Exception('Password tidak cocok.');
            }
            $data->update([
                'password' => Hash::make($req->password)
            ]);
            return [
                'code' => 200,
                'success' => true,
                'message' => 'Password berhasil diubah.',
                'url' => url('backend/user')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }
    function delete(Request $req)
    {
        try {
            $data = User::find($req->id);
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

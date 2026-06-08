<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPemasok;
use App\Models\UserPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class AuthController extends Controller
{
    function index()
    {
        return view('backend.auth.login');
    }

    function login(Request $req)
    {
        try {
            $get = User::where('email', $req->email)->first();
            if (!$get || !Hash::check($req->password, $get->password)) {
                throw new InvalidArgumentException('Ups, email atau password yang anda masukan salah', 404);
            }
            if ($get->active != 'yes') {
                throw new InvalidArgumentException('Ups, akun anda di nonaktifkan', 422);
            }
            auth()->guard('web')->login($get, $remember = true);
            return [
                'code' => 200,
                'success' => true,
                'url' => url('backend/dashboard')
            ];
        } catch (\Throwable $th) {
            return errors($th);
        }
    }

    function logout(Request $request)
    {
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

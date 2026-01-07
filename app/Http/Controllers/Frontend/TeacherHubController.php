<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Blog;
use App\Models\Event;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TeacherHubController extends Controller
{
    public function login()
    {
        return view('frontend.teacher-hub-login');
    }

    public function register()
    {
        return view('frontend.teacher-hub-register');
    }

    public function postRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'required|string',
            'domisili' => 'required|string',
            'schoolName' => 'required|string',
            'teachingField' => 'required|string',
            'gender' => 'required|in:laki-laki,perempuan',
            'birthDate' => 'required|date',
            'password' => 'required|min:6',
            'confirmPassword' => 'required|same:password',
        ], [
            'fullName.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'phone.required' => 'Nomor telepon wajib diisi',
            'domisili.required' => 'Domisili wajib diisi',
            'schoolName.required' => 'Nama sekolah wajib diisi',
            'teachingField.required' => 'Bidang mengajar wajib diisi',
            'gender.required' => 'Jenis kelamin wajib diisi',
            'birthDate.required' => 'Tanggal lahir wajib diisi',
            'birthDate.date' => 'Format tanggal lahir tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'confirmPassword.required' => 'Konfirmasi password wajib diisi',
            'confirmPassword.same' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Parse tanggal lahir
            $birthDate = Carbon::createFromFormat('d/m/Y', $request->birthDate)->format('Y-m-d');

            $teacher = Teacher::create([
                'name' => $request->fullName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'domisili' => $request->domisili,
                'school_name' => $request->schoolName,
                'teaching_field' => $request->teachingField,
                'gender' => $request->gender,
                'birth_date' => $birthDate,
                'status' => 'active',
            ]);

            // Auto login setelah register
            Auth::guard('teacher')->login($teacher);

            return redirect()->route('teacher.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di Teachers Hub.');
        } catch (\Throwable $th) {
            return back()
                ->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function postLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::guard('teacher')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('teacher.dashboard')->with('success', 'Login berhasil! Selamat datang kembali.');
        }

        return back()
            ->with('error', 'Email atau password salah.')
            ->withInput();
    }

    public function dashboard()
    {
        $teacher = Auth::guard('teacher')->user();

        if (!$teacher) {
            return redirect()->route('teacher.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get blogs for "Informasi Khusus Guru" section
        $data['blogs'] = Blog::with('category')
            ->where('visible', 'yes')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Get events for "Informasi Pelatihan" section
        $data['events'] = Event::with('category')
            ->where('visible', 'yes')
            ->whereDate('date', '>=', now())
            ->orderBy('date', 'asc')
            ->take(4)
            ->get();

        // Get products for "E-Book Yudhistira" section
        $data['products'] = Product::where('visible', 'yes')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        $data['teacher'] = $teacher;

        return view('frontend.teacher-hub-dashboard', $data);
    }

    public function logout(Request $request)
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('teacher.login')->with('success', 'Anda telah logout.');
    }
}

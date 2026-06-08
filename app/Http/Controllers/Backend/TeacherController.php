<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\City;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * Daftar akun teacher.
     */
    public function index(Request $request)
    {
        $query = Teacher::query()->orderBy('created_at', 'desc');

        if ($request->filled('status') && in_array($request->status, ['active', 'inactive'], true)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = '%' . $request->search . '%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search)
                    ->orWhere('phone', 'like', $search)
                    ->orWhere('npsn', 'like', $search)
                    ->orWhere('school_name', 'like', $search);
            });
        }

        $data['collection'] = $query->paginate(15)->withQueryString();
        $data['title'] = 'Teachers';

        return view('backend.teachers.index', $data);
    }

    /**
     * Form edit teacher.
     */
    public function edit($id)
    {
        $item = Teacher::find($id);
        if (!$item) {
            return redirect(url('backend/teachers'))->with('error', 'Teacher tidak ditemukan.');
        }
        
        return view('backend.teachers.edit', [
            'title' => 'Edit Teacher',
            'item' => $item,
            'city' => City::get(),
            'mapel' => MataPelajaran::get(),
        ]);
    }

    /**
     * Simpan update teacher.
     */
    public function update(Request $request)
    {
        try {
            $teacher = Teacher::findOrFail($request->id);

            $data = [
                'npsn' => $request->npsn,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'domisili' => $request->domisili,
                'school_name' => $request->school_name,
                'teaching_field' => $request->teaching_field,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date ?: null,
                'max_request' => $request->max_request
            ];

            if ($request->hasFile('photo')) {
                $data['photo'] = $this->uploadNormal($request->photo, 'Teacher');
            }

            $teacher->update($data);

            return [
                'code' => 200,
                'success' => true,
                'message' => 'Data teacher berhasil diubah.',
                'url' => url('backend/teachers'),
            ];
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle status aktif/nonaktif.
     */
    public function updateStatus(Request $request)
    {
        try {
            $teacher = Teacher::findOrFail($request->id);
            $newStatus = $teacher->status === 'active' ? 'inactive' : 'active';
            $teacher->update(['status' => $newStatus]);

            return [
                'code' => 200,
                'success' => true,
                'message' => $newStatus === 'active' ? 'Akun diaktifkan.' : 'Akun dinonaktifkan.',
                'status' => $newStatus,
            ];
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Form ganti password.
     */
    public function password($id)
    {
        $item = Teacher::find($id);
        if (!$item) {
            return redirect(url('backend/teachers'))->with('error', 'Teacher tidak ditemukan.');
        }

        return view('backend.teachers.password', [
            'title' => 'Ganti Password Teacher',
            'item' => $item,
        ]);
    }

    /**
     * Simpan password baru.
     */
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|min:6|confirmed',
            ], [
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);

            $teacher = Teacher::findOrFail($request->id);
            $teacher->update([
                'password' => Hash::make($request->password),
            ]);

            return [
                'code' => 200,
                'success' => true,
                'message' => 'Password berhasil diubah.',
                'url' => url('backend/teachers'),
            ];
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            if ($th instanceof \Illuminate\Validation\ValidationException) {
                $message = $th->validator->errors()->first();
            }
            return response()->json([
                'code' => 422,
                'success' => false,
                'message' => $message,
            ], 422);
        }
    }
}

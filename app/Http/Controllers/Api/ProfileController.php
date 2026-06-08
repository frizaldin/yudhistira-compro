<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * GET /api/v1/profile
     * Data user (teacher) yang sedang login.
     */
    public function show(): JsonResponse
    {
        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Harap Login Terlebih Dahulu',
            ], 402);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatTeacher($teacher),
        ]);
    }

    /**
     * PUT /api/v1/profile
     * Update profile teacher (nama, email, phone, domisili, sekolah, dll).
     */
    public function update(Request $request): JsonResponse
    {
        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Harap Login Terlebih Dahulu',
            ], 402);
        }

        try {
            $validated = $request->validate([
                'npsn' => 'sometimes|string|max:100',
                'name' => 'sometimes|string|max:255',
                'email' => ['sometimes', 'email', 'max:255', Rule::unique('teachers', 'email')->ignore($teacher->id)],
                'phone' => 'nullable|string|max:50',
                'domisili' => 'nullable|string|max:255',
                'school_name' => 'nullable|string|max:255',
                'teaching_field' => 'nullable|string|max:255',
                'gender' => 'nullable|in:laki-laki,perempuan',
                'birth_date' => 'nullable|date',
            ], [
                'npsn.string' => 'NPSN harus berupa teks.',
                'npsn.max' => 'NPSN maksimal 100 karakter.',
                'name.string' => 'Nama harus berupa teks.',
                'name.max' => 'Nama maksimal 255 karakter.',
                'email.email' => 'Format email tidak valid.',
                'email.max' => 'Email maksimal 255 karakter.',
                'email.unique' => 'Email sudah digunakan, silakan gunakan email lain.',
                'phone.string' => 'Nomor telepon harus berupa teks.',
                'phone.max' => 'Nomor telepon maksimal 50 karakter.',
                'domisili.string' => 'Domisili harus berupa teks.',
                'domisili.max' => 'Domisili maksimal 255 karakter.',
                'school_name.string' => 'Nama sekolah harus berupa teks.',
                'school_name.max' => 'Nama sekolah maksimal 255 karakter.',
                'teaching_field.string' => 'Bidang ajar harus berupa teks.',
                'teaching_field.max' => 'Bidang ajar maksimal 255 karakter.',
                'gender.in' => 'Jenis kelamin harus laki-laki atau perempuan.',
                'birth_date.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            ]);

            if ($request->hasFile('photo')) {
                $request->validate(['photo' => 'image|max:2048']);
                $path = $request->file('photo')->store('teachers', 'public');
                $validated['photo'] = 'storage/' . $path;
            }

            $teacher->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil diperbarui.',
                'data' => $this->formatTeacher($teacher->fresh()),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * PUT /api/v1/profile/password
     * Ganti password teacher.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $teacher = Auth::guard('api')->user();
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Harap Login Terlebih Dahulu',
            ], 402);
        }

        try {
            $request->validate([
                'current_password' => 'required|string',
                'password' => 'required|string|min:6|confirmed',
            ], [
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);

            if (!Hash::check($request->current_password, $teacher->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password saat ini salah.',
                ], 422);
            }

            $teacher->update([
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    private function formatTeacher(Teacher $teacher): array
    {
        $photo = $teacher->photo;
        if ($photo && !str_starts_with($photo, 'http')) {
            $photo = asset($photo);
        }

        return [
            'id' => $teacher->id,
            'npsn' => $teacher->npsn,
            'name' => $teacher->name,
            'email' => $teacher->email,
            'phone' => $teacher->phone,
            'domisili' => $teacher->domisili,
            'school_name' => $teacher->school_name,
            'teaching_field' => $teacher->teaching_field,
            'gender' => $teacher->gender,
            'birth_date' => $teacher->birth_date?->format('Y-m-d'),
            'photo' => $photo,
            'status' => $teacher->status,
            'created_at' => $teacher->created_at?->toIso8601String(),
            'updated_at' => $teacher->updated_at?->toIso8601String(),
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\MataPelajaran;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class SignUpController extends Controller
{
    function container(Request $req)
    {
        DB::beginTransaction();

        $_segment = last($req->segments());
        $result = '';

        try {
            if ($_segment == 'data') {
                $result = $this->data($req);
            } elseif ($_segment == 'run') {
                $result = $this->run($req);
            }

            DB::commit();

            return response()->json([
                'code' => 200,
                'success' => true,
                'result' => $result
            ]);
        } catch (\Throwable $th) {
            DB::rollback();

            return response()->json([
                'code' => 500,
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * PRIVATE
     */
    private function data(Request $req)
    {
        $cities = Cache::remember('cities', '9000', function() {
            return City::orderBy('province_code', 'asc')->get();
        });

        $mata_pelajaran = MataPelajaran::select('id', 'title')->orderBy('title', 'asc')->get();

        return [
            'cities' => $cities,
            'mata_pelajaran' => $mata_pelajaran
        ];
    }

    private function run(Request $req)
    {
        /**
         * VALIDATED
         */
        $req->validate([
            'code_sales'         => 'required|string|max:50',
            'name'               => 'required|string|max:100',
            'domisili'           => 'required|string|max:100',
            'email'              => 'required|email|unique:teachers,email',
            'phone'              => 'required|string|max:15',
            'school_name'        => 'required|string|max:150',
            'teaching_field'     => 'required|string|max:100',
            'gender'             => [
                'required',
                Rule::in(['laki-laki', 'perempuan']),
            ],
            'birth_date'         => 'required|date',
            'password'           => 'required|string|min:8',
            'confirmation_password' => 'required|same:password',
        ]);

        /**
         * CHECK KODE SALES
         */
        $__checkSales = Http::withHeaders([
            'key' => env('API_KEY'),
        ])->post(env('API_URL').'check/referral_sales', [
            'referral_code' => $req->code_sales
        ]);
        $checkSales = $__checkSales->json();
        if ($checkSales['code'] != 200) {
            throw new InvalidArgumentException('Invalid Kode Referral Sales', 500);
        }

        /**
         * CONTAINER
         */
        $post = $req->only([
            'code_sales', 'name', 'domisili', 'email', 'phone', 'school_name', 'teaching_field', 'birth_date'
        ]);
        $post = array_merge($post, [
            'password' => Hash::make($req->password)
        ]);

        $store = Teacher::create($post);

        if (!$token = Auth::guard('api')->login($store)) {
            throw new InvalidArgumentException('Email atau password salah', 500);
        }

        return [
            'access_token' => $token,
            'data' => $store,
        ];
    }
}

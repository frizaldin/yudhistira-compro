<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function index()
    {
        $user = null;
        if (auth()->user()) {
            $user = auth()->user();
        } elseif (auth()->guard('company')->user()) {
            $user = auth()->guard('company')->user();
        } elseif (auth()->guard('supplier')->user()) {
            $user = auth()->guard('supplier')->user();
        }
        $data['user'] = $user;
        return view('backend.dashboard.index', $data);
    }
}

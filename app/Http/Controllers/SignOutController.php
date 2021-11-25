<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SignOutController extends Controller
{
    public function index()
    {
        Session::flush();

        Auth::logout();

        return redirect('/');
    }
}

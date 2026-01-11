<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('GoogleAuth.login');
    }

    /**
     * Show registration form
     */
    public function registerForm()
    {
        return view('GoogleAuth.register');
    }

    /**
     * Show dashboard
     */
    public function dashboard()
    {
        return view('Dashboard.dashboard');
    }
}

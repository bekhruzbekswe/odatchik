<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('Auth.login');
    }

    /**
     * Show registration form
     */
    public function registerForm()
    {
        return view('Auth.register');
    }

    /**
     * Show dashboard
     */
    public function dashboard()
    {
        return view('Dashboard.dashboard');
    }
}

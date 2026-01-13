<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * 
     * Register user
     */

    public function register(Request $request)
    {
        return $this->service->register($request);
    }

    /**
     * Login user
     */

    public function login(Request $request)
    {
        return $this->service->login($request);
    }

    /**
     * Logged user
     */
    public function user(Request $request)
    {
        return $this->service->user($request);
    }

    /**
     * Logout
     */

    public function logout(Request $request)
    {
        return $this->service->logout($request);
    }
}

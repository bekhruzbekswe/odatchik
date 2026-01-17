<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

/**
 * @group Authentication
 *
 * APIs for user authentication.
 */
class AuthController extends Controller
{
    public function __construct(
        protected AuthService $service
    ) {}

    /**
     * Register
     *
     * Create a new user account.
     *
     * @unauthenticated
     *
     * @response 201 {
     *   "message": "Registered successfully",
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "created_at": "2026-01-17T12:00:00.000000Z",
     *     "updated_at": "2026-01-17T12:00:00.000000Z"
     *   }
     * }
     */
    public function register(RegisterRequest $request)
    {
        return $this->service->register($request);
    }

    /**
     * Login
     *
     * Authenticate a user and generate an access token.
     *
     * @unauthenticated
     *
     * @response 200 {
     *   "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...",
     *   "token_type": "Bearer",
     *   "expires_at": "2027-01-17T12:00:00.000000Z",
     *   "user": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com"
     *   }
     * }
     * @response 401 {
     *   "message": "Invalid credentials"
     * }
     */
    public function login(LoginRequest $request)
    {
        return $this->service->login($request);
    }

    /**
     * Get authenticated user
     *
     * Get the currently authenticated user's details.
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "John Doe",
     *   "email": "john@example.com",
     *   "email_verified_at": null,
     *   "created_at": "2026-01-17T12:00:00.000000Z",
     *   "updated_at": "2026-01-17T12:00:00.000000Z"
     * }
     */
    public function user(Request $request)
    {
        return $this->service->user($request);
    }

    /**
     * Logout
     *
     * Revoke the current access token.
     *
     * @response 200 {
     *   "message": "Logged out"
     * }
     */
    public function logout(Request $request)
    {
        return $this->service->logout($request);
    }
}

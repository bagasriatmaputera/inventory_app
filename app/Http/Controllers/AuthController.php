<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authServices;
    public function __construct(AuthServices $authServices)
    {
        $this->authServices = $authServices;
    }
    public function register(RegisterRequest $request)
    {
        $user = $this->authServices->register($request->validated());
        return response()->json([
            'status' => 'success',
            'data' => $user
        ], 201);
    }
    public function login(LoginRequest $request)
    {
        return $this->authServices->login($request->validated());
    }
    public function tokenLogin(LoginRequest $request)
    {
        return $this->authServices->tokenLogin($request->validated());
    }
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout Successfully'
        ]);
    }
    public function user(Request $request)
    {
        return response()->json(['data' => new UserResource($request->user())]);
    }
}

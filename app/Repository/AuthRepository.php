<?php

namespace App\Repository;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{

    public function register(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'photo' => $data['photo'],
        ]);
    }
    public function login(array $data)
    {
        $credetials = [
            'email' => $data['email'],
            'password' => $data['password']
        ];

        if (!Auth::attempt($credetials)) {
            return response()->json([
                'message' => 'The provided credentials do not match aur records'
            ]);
        }

        // Mencegah Session Fixation Attack, Tanpa regenerate, session ID tetap sama sebelum dan sesudah login.
        request()->session()->regenerate();

        $user = Auth::user();

        return response()->json([
            'message' => 'Login Successfully',
            'data' => new UserResource($user->load('roles'))
        ]);
    }
    public function tokenLogin(array $data)
    {
        if (!Auth::attempt($data['email'], $data['password'])) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => 'Login Successfully',
            'token' => $token,
            'data' => new UserResource($user->load('roles'))
        ]);
    }
}

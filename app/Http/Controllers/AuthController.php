<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:28',
            'email' => 'required|email|unique:users',
            'password' => [
                'required',
                Password::min(8)
                    ->symbols()
                    ->letters()
                    ->mixedCase()
                    ->numbers()
            ]
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Berhasil Registrasi',
            'data' => $user
        ]);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token =  $user->createToken("$user->name.$user->id")->plainTextToken;
                return response()->json([
                    'status' => true,
                    'message' => 'Berhasil Login',
                    'data' => $user,
                    'token' => $token
                ]);
            }
            return response()->error('Password tidak cocok');
        }
        return response()->error('Email belum terdaftar');
    }

    public function logout(Request $request)
    {
        $user = $request->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Berhasil Logout',
            'data' => $user
        ]);
    }
}

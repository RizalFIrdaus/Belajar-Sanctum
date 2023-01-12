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
}

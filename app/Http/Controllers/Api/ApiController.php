<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    //Register API
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed",
        ]);

        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);
        return response()->json([
            "status" => true,
            "message" => "User created successfully!",
        ], 200);
    }


    //Login API
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken("myToken")->accessToken;
            return response()->json([
                "status" => true,
                "message" => "Login Successful!",
                "token" => $token
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Invalid login details!"
            ]);
        }

    }

    //Profile API
    public function profile(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            "status" => true,
            "message" => "Profile information",
            "user" => $user
        ]);
    }

    //Logout API
    public function logout(Request $request)
    {
        auth()->user()->token()->revoke();
        return response()->json([
            "status" => true,
            "message" => "Logout successful!"
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if(Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            $user = Auth::user();
            $user->token = $user->createToken($user->email)->accessToken;

            return $user;
        }

        return response(['message' => 'Unauthenticated.'], 401);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password'])
        ]);

        $user->token = $user->createToken($user->email)->accessToken;

        return $user;
    }

    public function logout()
    {
        Auth::user()->token()->revoke();

        return response()->json([
            'message' => 'Deslogado com sucesso'
        ]);
    }
}

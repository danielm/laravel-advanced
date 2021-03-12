<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class UserTokenController extends Controller
{
    public function login(LoginRequest $request)
    {
        if(Auth::attempt($request->only('email', 'password'))){
            return response()->json([
                'token' => $request->user()->createToken($request->get('source'))->plainTextToken,
                'message' => 'Success',
            ]);
        }

        return response()->json([
            'message' => 'Unauthorized'
        ], 401);
    }
}

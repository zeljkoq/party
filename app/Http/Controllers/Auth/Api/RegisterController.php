<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = new User;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->save();

        $credentials = request(['username', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'access_token' => $token
        ]);
    }
}

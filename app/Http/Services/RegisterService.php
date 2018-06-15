<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.6.18.
 * Time: 13.11
 */

namespace App\Http\Services;


use App\Models\User;

class RegisterService
{
    public function register($request)
    {
        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->save();

        $user->roles()->attach($request->userRole);

        $credentials = request(['email', 'password']);
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
<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Login de usuario de la app
     * Devuelve un api_token propio
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = AppUser::where('email', $data['email'])->first();

        if (
            !$user ||
            !$user->password ||
            !Hash::check($data['password'], $user->password)
        ) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $plainToken = Str::random(64);

        $user->update([
            'api_token' => hash('sha256', $plainToken),
        ]);

        return response()->json([
            'token' => $plainToken,
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
}

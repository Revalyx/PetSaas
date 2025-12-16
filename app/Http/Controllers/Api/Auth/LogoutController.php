<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        // El usuario viene ya resuelto por el middleware auth.app
        $user = $request->user();

        if ($user) {
            $user->update([
                'api_token' => null,
            ]);
        }

        return response()->json([
            'ok' => true
        ]);
    }
}

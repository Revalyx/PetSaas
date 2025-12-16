<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use Google\Client as GoogleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GoogleLoginController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'id_token' => ['required', 'string'],
        ]);

        $client = new GoogleClient([
            'client_id' => config('services.google.client_id'),
        ]);

        $payload = $client->verifyIdToken($data['id_token']);

        if (!$payload) {
            return response()->json(['message' => 'Invalid Google token'], 401);
        }

        $googleId = $payload['sub'];
        $email    = $payload['email'] ?? null;
        $name     = $payload['name'] ?? 'Google User';

        if (!$email) {
            return response()->json(['message' => 'Google account has no email'], 422);
        }

        $user = AppUser::where('google_id', $googleId)->first()
            ?? AppUser::where('email', $email)->first();

        if (!$user) {
            $user = AppUser::create([
                'email'     => $email,
                'name'      => $name,
                'password'  => null,
                'google_id' => $googleId,
                'provider'  => 'google',
            ]);
        } else {
            $user->update([
                'google_id' => $user->google_id ?? $googleId,
                'provider'  => 'google',
            ]);
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

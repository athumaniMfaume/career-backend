<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
public function store(LoginRequest $request)
{
    $request->authenticate();
    $user = Auth::user();

    return response()->json([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ],
        'token' => $user->createToken('authToken')->plainTextToken,
    ]);
}


    /**
     * Destroy an authenticated session.
     */
public function destroy(Request $request)
{
    if ($request->user()) {
        $request->user()->currentAccessToken()->delete();
    }

    return response()->json(['message' => 'Logged out successfully']);
}

}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // List all users
public function index()
{
    $users = User::paginate(5);

    if ($users->isEmpty()) {
        return response()->json([
            'message' => 'No users found.'
        ], 404);
    }

    return response()->json($users);
}

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    }

    // Show specific user
    public function show($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    // Update user details

   public function update(Request $request, $id)
{
    $user = User::find($id);

    if (! $user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
        'password' => ['nullable', 'confirmed', Password::defaults()],
    ]);

    if ($request->filled('name')) {
        $user->name = $request->name;
    }

    if ($request->filled('email')) {
        $user->email = $request->email;
    }

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return response()->json([
        'message' => 'User updated successfully',
        'user' => $user,
    ]);
}


    // Delete user
    public function destroy($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

       return response()->json(['message' => 'User deleted successfully'], 200);

    }
}

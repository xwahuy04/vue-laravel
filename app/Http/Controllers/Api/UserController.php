<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|max:255',
            'role_id' => 'required|'.Rule::in(['1', '2', '3', '4' ]),
        ]);

        $request['password'] = Hash::make($request->password);
        $user = User::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Create User successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]
        ], 200);
    }
}

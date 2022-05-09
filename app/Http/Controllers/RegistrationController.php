<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function __invoke(Request $request): UserResource
    {
        $fields = $request->validate([
            'username' => ['string', 'min:3', 'max:255', 'unique:users', 'required_without:email'],
            'email' => ['email', 'required_without:username', 'unique:users'],
            'password' => ['string', 'between:8,20', 'required', 'confirmed']
        ]);

        $user = User::create($fields);

        $user->token = $user->createToken('auth')->plainTextToken;

        return new UserResource($user);
    }
}

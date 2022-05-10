<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request): UserResource | JsonResponse
    {
        $fields = $request->validate([
            'username' => ['string', 'required_without:email'],
            'email' => ['email', 'required_without:username'],
            'password' => ['string', 'required']
        ]);

        if (!Auth::attempt($fields)) {
            return response()->json([
                'message' => 'Incorrect Details'
            ], 401);
        }

        $user = Auth::user();
        $user->token = $user->createToken('auth')->plainTextToken;

        return new UserResource($user);
    }
}

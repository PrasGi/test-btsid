<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }
    public function register(Request $request)
    {
        $validate = Validator::make(
            $request->only('name', 'email', 'password'),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]
        );

        if ($validate->fails()) {
            return response()->json([
                'status_code' => 400,
                'message' => $validate->errors()
            ], 400);
        }

        if ($user = $this->userModel->create($validate->getData())) {
            return response()->json([
                'status_code' => 200,
                'message' => 'User created successfully',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'status_code' => 500,
                'message' => 'Internal server error'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->only('email', 'password'), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status_code' => 400,
                'message' => $validate->errors()
            ], 400);
        }

        if (!$token = auth()->attempt($validate->getData())) {
            return response()->json([
                'status_code' => 401,
                'message' => 'Unauthorized'
            ], 401);
        }

        return response()->json([
            'status_code' => 200,
            'message' => 'User logged in successfully',
            'data' => [
                'token' => $token,
                'token_type' => 'bearer',
            ]
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Login api
     */
    public function login(Request $request)
    {
        // Define the validation rules
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3',
            'password' => 'required|string|min:6',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        // Get validated data
        $validated = $validator->validated();

        // Find the user by username (or phone, depending on your model)
        $user = $this->userRepository->findUserByUsername($validated['username']);

        // If user not found or password is incorrect
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid username or password.',
            ], 401);
        }

        // Generate the authentication token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return the response with the token and user data
        return response()->json([
            'success' => true,
            'data' => [
                'token' => $token,
                'user' => $user,
            ],
            'message' => 'User updated successfully.',
        ], 200);
    }

    public function logout(Request $request)
    {
        // Revoke the token for the authenticated user
        $request->user()->currentAccessToken()->delete();

        // Return a response indicating the user has logged out
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ], 200);
    }
}


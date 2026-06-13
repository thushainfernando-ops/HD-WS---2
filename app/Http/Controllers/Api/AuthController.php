<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle API login request
     * 
     * Validates user credentials and returns a Sanctum API token
     * that can be used for subsequent authenticated requests.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @example
     * POST /api/login
     * {
     *   "email": "user@example.com",
     *   "password": "password123"
     * }
     */
    public function login(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Find user by email
        $user = User::where('email', $validated['email'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($validated['password'], $user->password)) {
            // Return 401 Unauthorized response with error message
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
                'error' => true,
            ], 401);
        }

        // Generate Sanctum API token
        // This token will be used in the Authorization: Bearer <token> header
        $token = $user->createToken('api-token')->plainTextToken;

        // Return successful login response with token and user data
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], 200);
    }

    /**
     * Handle API logout request
     * 
     * Deletes the current access token, effectively logging out the user
     * and invalidating any further requests with this token.
     * Requires authentication via auth:sanctum middleware.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * 
     * @example
     * POST /api/logout
     * Headers: Authorization: Bearer <token>
     */
    public function logout(Request $request)
    {
        // Delete the current access token
        // This prevents the token from being used for future requests
        $request->user()->currentAccessToken()->delete();

        // Return success response
        return response()->json([
            'message' => 'Logged out successfully',
            'success' => true,
        ], 200);
    }
}

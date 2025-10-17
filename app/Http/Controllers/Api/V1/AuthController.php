<?php
//Developer: Taslimul Islam | Reviewed: 2025‐10‐18

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;

class AuthController extends Controller
{
    /**
     * User authentication and Sanctum token generate.
     *
     * @param LoginRequest $request Validated login credentials.
     * @return \Illuminate\Http\JsonResponse JSON response with token and user data.
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (!Auth::attempt($credentials)) {
                return ApiResponse::error('Invalid credentials', 401);
            }

            $user = User::where('email', $credentials['email'])->firstOrFail();
            $token = $user->createToken('api-token')->plainTextToken;

            return ApiResponse::success([
                'token' => $token,
                'user'  => $user,
            ], 'Login successful');
        } catch (Exception $e) {
            return ApiResponse::error('Login failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Logut the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse JSON response confirming logout.
     */
    public function logout()
    {
        try {
            auth()->user()->currentAccessToken()->delete();

            return ApiResponse::success([], 'Logged out successfully');
        } catch (Exception $e) {
            return ApiResponse::error('Logout failed: ' . $e->getMessage(), 500);
        }
    }

}
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use PasswordValidationRules;
    // login function
    public function login(Request $request)
    {
        try {
            $request->validate([
                'nip' => 'required',
                'password' => 'required',
                'device_id' => 'required'
            ]);

            $credentials = request(['nip', 'password']);
            if (!Auth::guard('api')->attempt($credentials)) {
                return ResponseFormatter::error(
                    [
                        'message' => 'Unauthorized'
                    ],
                    'Authentication Failed',
                    401
                );
            }
            $user = Employee::with(['office'])->where('nip', $request->nip)->first();

            // Check if device ID matches
            if ($user->device_id == null) {
                $user->device_id = $request->device_id;
                $user->update();
            }
            if ($user->device_id != $request->device_id) {
                return ResponseFormatter::error(
                    [
                        'message' => 'Unauthorized'
                    ],
                    'Device ID mismatch',
                    401
                );
            }

            $tokenResult = $user->createToken('auth_token')->plainTextToken;

            return ResponseFormatter::success(
                [
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                    'user' => $user,
                ],
                'Authenticated'
            );
        } catch (\Exception $e) {
            return ResponseFormatter::error(
                [
                    'status' => 'error',
                ],
                $e->getMessage(),
                400
            );
        }
    }

    // logout
    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success($token, 'Token Revoked');
    }

    // unauthorized
    public function unauthorized(Request $request)
    {
        return ResponseFormatter::error(
            null,
            'Unauthorized',
            401,
        );
    }
}
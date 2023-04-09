<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use PasswordValidationRules;
    // login
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

            $presence = Presence::where('employee_id', $user->nip)->where('presence_date', Carbon::now()->format('Y-m-d'))->first();
            if (!$presence && Carbon::now()->format('l') != 'Saturday' && Carbon::now()->format('l') != 'Sunday') {
                Presence::create([
                    'employee_id' => $user->nip,
                    'office_id' => $user->office_id,
                    'presence_date' => Carbon::now()->format('Y-m-d'),
                ]);
            }

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


    // edit user
    public function updateProfile(Request $request)
    {
        try {
            $data = $request->all();
            if ($request->hasFile('profile_photo_path')) {
                $data['profile_photo_path'] = $request->file('profile_photo_path')->store(
                    'assets/employee',
                    'public'
                );
            }
            $user = Auth::user();
            $user->update($data);

            return ResponseFormatter::success($user, 'Profile updated');
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $th
                ],
                'Update Profile Failed',
                500
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
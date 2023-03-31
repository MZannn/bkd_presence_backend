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
                    500
                );
            }
            $user = Employee::with(['office'])->where('nip', $request->nip)->first();

            $presence = Presence::all()->where('employee_id', $user->nip)->where('presence_date', Carbon::now()->format('Y-m-d'))->first();
            if (!$presence) {
                Presence::create([
                    'employee_id' => $user->nip,
                    'office_id' => $user->office_id,
                    'presence_date' => Carbon::now()->format('Y-m-d'),
                ]);
                $presence = Presence::all()->where('employee_id', $user->nip);
                if ($user->device_id == null) {
                    $user->device_id = $request['device_id'];
                    $user->update();
                }
                if ($user->device_id != $request['device_id']) {
                    return ResponseFormatter::error(
                        [
                            'message' => 'Unauthorized'
                        ],
                        'Authentication Failed',
                        500
                    );
                }
                if (!Hash::check($request->password, $user->password, [])) {
                    throw new \Exception('Invalid Credentials');
                }


                $tokenResult = $user->createToken('authToken')->plainTextToken;
                return ResponseFormatter::success(
                    [
                        'access_token' => $tokenResult,
                        'token_type' => 'Bearer',
                        'user' => $user,
                        'presences' => $presence
                    ],
                    'Authenticated'
                );
            }
            $presence = Presence::all()->where('employee_id', $user->nip);
            if ($user->device_id == null) {
                $user->device_id = $request['device_id'];
                $user->update();
            }
            if ($user->device_id != $request['device_id']) {
                return ResponseFormatter::error(
                    [
                        'message' => 'Unauthorized'
                    ],
                    'Authentication Failed',
                    500
                );
            }
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }


            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success(
                [
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                    'user' => $user,
                    'presences' => $presence
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

    // fetch user
    public function fetch(Request $request)
    {
        $user = Auth::user();
        $user = Employee::with(['office'])->where('nip', $user->nip)->first();
        $presence = Presence::all()->where('employee_id', $user->nip)->where('presence_date', Carbon::now()->format('Y-m-d'))->first();
        if (Auth::check() == true) {
            if (!$presence) {
                Presence::create([
                    'employee_id' => $user->nip,
                    'office_id' => $user->office_id,
                    'presence_date' => Carbon::now()->format('Y-m-d'),
                ]);
                $presence = Presence::all()->where('employee_id', $user->nip);
                return ResponseFormatter::success([
                    'user' => $user,
                    'presences' => $presence
                ], 'Data profile user berhasil diambil');
            }
            $presence = Presence::all()->where('employee_id', $user->nip);
            return ResponseFormatter::success([
                'user' => $user,
                '   ' => $presence
            ], 'Data profile user berhasil diambil');
        } else {
            return ResponseFormatter::error(Auth::check());
        }
    }

    // update photo
    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), ['file' => 'required|image|max:2048']);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'error' => $validator->errors(),
            ], 'Upload photo fails', 400);
        }

        if ($request->file('file')) {
            $file = $request->file->store('assets/user', 'public');

            $user = Auth::user();
            $user->profile_photo_path = $file;
            $user->update();
            return ResponseFormatter::success([$file], 'File successfully Uploaded');
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
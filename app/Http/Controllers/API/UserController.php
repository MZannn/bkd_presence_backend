<?php

namespace App\Http\Controllers\API;

use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use PasswordValidationRules;
    // login
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error(
                    [
                        'message' => 'Unauthorized'
                    ],
                    'Authentication Failed',
                    500
                );
            }
            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success(
                [
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                    'user' => $user
                ],
                'Authenticated'
            );
        } catch (\Exception $e) {
            return ResponseFormatter::error()(
                [
                    'status' => 'error',
                ],
                $e->getMessage(),
                400
            );
        }
    }

    // register
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|max:255'

            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return ResponseFormatter::success(
                [
                    'user' => $user
                ],
                'Authenticated'
            );
        } catch (\Exception $e) {
            return ResponseFormatter::error()(
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
        if (Auth::check() == true) {
            return ResponseFormatter::success($request->user());
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

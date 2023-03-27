<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('pages.admin.changePassword');
    }

    public function updatePasswordAdmin(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);
        $user = User::find(auth()->user()->id);
        $hash = Hash::check($request->old_password, $user->password);
        // dd($hash);
        if (!$hash) {
            return redirect()->back()->with('alert', 'Password Lama Salah');
        } else {
            User::find($user->id)->update(['password' => Hash::make($request->password)]);
            return redirect()->route('dashboard')->with('alert', 'Password Berhasil Diubah');
        }
    }
}
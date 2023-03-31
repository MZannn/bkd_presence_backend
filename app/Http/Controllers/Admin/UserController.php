<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = User::with('office')->whereNotNull('office_id')->get();
        return view('pages.admin.user.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $offices = Office::all();
        return view('pages.admin.user.create', compact('offices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'roles' => 'required:in:ADMIN,OFFICE HEAD',
            'office_id' => 'required',
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['office_id'] = $request->office_id;
        User::create($data);
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        //
    }
    public function changePassword()
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
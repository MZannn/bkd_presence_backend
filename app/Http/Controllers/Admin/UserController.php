<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
        return redirect()->route('user.index')->with('alert', 'Data Berhasil Ditambahkan');
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
    public function edit(string $id)
    {
        $item = User::find($id);
        return view('pages.admin.user.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $item = User::findOrFail($id);
        $item->update($data);
        return redirect()->route('user.index')->with('alert', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = User::findOrFail($id);
        $item->delete();
        return redirect()->route('user.index')->with('alert', 'Data Berhasil Dihapus');
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
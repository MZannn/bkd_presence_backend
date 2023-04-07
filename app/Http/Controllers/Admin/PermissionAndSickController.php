<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\PermissionAndSick;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionAndSickController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user() && Auth::user()->roles == 'SUPER ADMIN') {
            $offices = Office::all();
            if ($request->has('search')) {
                $items = PermissionAndSick::with(['office', 'employee', 'presence'])->where('employee_id', 'like', '%' . $request->search . '%')->paginate(10);
                return view('pages.admin.permission-and-sick.index', compact('items', 'offices'));
            } else if ($request->office_id == null) {
                $data = PermissionAndSick::with(['office', 'employee', 'presence']);
                if ($data->first() != null) {
                    $items = PermissionAndSick::with(['office', 'employee', 'presence'])->where('office_id', $data->first()->office->id)->paginate(10);
                    return view('pages.admin.permission-and-sick.index', compact('items', 'offices'));
                }
            } else {
                $items = PermissionAndSick::with(['office', 'employee', 'presence'])->where('office_id', $request->office_id)->paginate(10);
                return view('pages.admin.permission-and-sick.index', compact('items', 'offices'));
            }
            $items = PermissionAndSick::with(['office', 'employee', 'presence'])->paginate(10);
            return view('pages.admin.permission-and-sick.index', compact('items', 'offices'));
        }
        if (Auth::user() && Auth::user()->roles == 'ADMIN') {
            if ($request->has('search')) {
                $items = PermissionAndSick::with(['office', 'employee', 'presence'])->where('employee_id', 'like', '%' . $request->search . '%')->paginate(10);
                return view('pages.admin.permission-and-sick.index', compact('items'));
            }
            $items = PermissionAndSick::with(['office', 'employee', 'presence'])->where('office_id', Auth::user()->office_id)->paginate(10);
            return view('pages.admin.permission-and-sick.index', compact('items'));
        }
    }

    public function validation(Request $request)
    {     
        $request->validate([
            'id'=> 'required',
            'employee_id' => 'required',
            'office_id' => 'required',
            'presence_id' => 'required',
            'date' => 'required|date',
            // 'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'required',
        ]);
        if ($request->status != 'PENDING') {
            Presence::where('id', $request->presence_id)->update([
                'attendance_entry_status' => $request->status,
                'attendance_exit_status' => $request->status,
            ]);
            PermissionAndSick::where('id', $request->id)->delete();  
        }
        return redirect()->route('permissionAndSick')->with('success', 'Data berhasil divalidasi');
    }
}
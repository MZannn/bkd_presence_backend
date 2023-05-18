<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\Office;
use App\Models\PermissionAndSick;
use App\Models\Presence;
use Carbon\Carbon;
use Grei\TanggalMerah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionAndSickController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user() && Auth::user()->roles == 'SUPER ADMIN') {
            $offices = Office::all();
            if ($request->has('search')) {
                $items = PermissionAndSick::with(['office', 'employee', 'presence'])->where('nip', 'like', '%' . $request->search . '%')->paginate(10);
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
                $items = PermissionAndSick::with(['office', 'employee', 'presence'])->where('nip', 'like', '%' . $request->search . '%')->paginate(10);
                return view('pages.admin.permission-and-sick.index', compact('items'));
            }
            $items = PermissionAndSick::with(['office', 'employee', 'presence'])->where('office_id', Auth::user()->office_id)->paginate(10);
            return view('pages.admin.permission-and-sick.index', compact('items'));
        }
    }

    public function validation(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'nip' => 'required',
            'office_id' => 'required',
            'presence_id' => 'required',
            'date' => 'required|date',
            // 'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'required',
        ]);
        if ($request->status == "SAKIT" || $request->status == "IZIN") {
            if ($request->start_date == $request->end_date) {
                $presence = Presence::where('id', $request->presence_id)
                    ->where('presence_date', $request->start_date)
                    ->where('nip', $request->nip)
                    ->first();
                $exists = Presence::where('presence_date', $request->start_date)
                    ->where('attendance_entry_status', "HADIR")
                    ->where('nip', $request->nip)
                    ->exists();
                $holidays = Holiday::pluck('holiday_date')->toArray();
                if (!$presence && Carbon::parse($request->start_date)->isWeekday() && !in_array($request->start_date, $holidays) && !$exists) {
                    Presence::create([
                        'presence_date' => $request->start_date,
                        'nip' => $request->nip,
                        'office_id' => $request->office_id,
                        'attendance_entry_status' => $request->status,
                        'attendance_exit_status' => $request->status,
                    ]);
                } else if ($presence && Carbon::parse($request->start_date)->isWeekday() && !in_array($request->start_date, $holidays) && !$exists) {
                    $presence->update([
                        'attendance_entry_status' => $request->status,
                        'attendance_exit_status' => $request->status,
                    ]);
                } else if (Carbon::parse($request->start_date)->isWeekend()) {
                    PermissionAndSick::findOrFail($request->id)->delete();
                    return redirect()->route('permission-and-sick.index')->with('alert', 'Data tidak bisa divalidasi karena sudah ada data kehadiran');
                } else if ($exists) {
                    PermissionAndSick::findOrFail($request->id)->delete();
                    return redirect()->route('permission-and-sick.index')->with('alert', 'Data tidak bisa divalidasi karena sudah ada data kehadiran');
                }
                return redirect()->route('permission-and-sick.index')->with('alert', 'Data berhasil divalidasi');
            } else if ($request->start_date != $request->end_date) {
                dd($request->all());
                $start_date = Carbon::parse($request->start_date);
                $end_date = Carbon::parse($request->end_date);
                $exists = Presence::where('presence_date', '>=', $request->start_date)->where('presence_date', '<=', $request->end_date)->where('attendance_entry_status', "HADIR")->exists();
                $holidays = Holiday::pluck('holiday_date')->toArray();
                for ($date = $start_date; $date <= $end_date; $date->addDay()) {
                    $presence = Presence::where('nip', $request->nip)->where('presence_date', $date->format('Y-m-d'))->first();
                    if (!$presence && Carbon::parse($date)->isWeekday() && !in_array($date->toDateString(), $holidays) && !$exists) {
                        Presence::create([
                            'nip' => $request->nip,
                            'office_id' => $request->office_id,
                            'presence_date' => $date->format('Y-m-d'),
                            'attendance_entry_status' => $request->status,
                            'attendance_exit_status' => $request->status,
                        ]);
                    } else if ($presence && Carbon::parse($date)->isWeekday() && !in_array($date->toDateString(), $holidays) && !$exists) {
                        $presence->update([
                            'attendance_entry_status' => $request->status,
                            'attendance_exit_status' => $request->status,
                        ]);
                    }
                }
                PermissionAndSick::findOrFail($request->id)->delete();
                return redirect()->route('permission-and-sick.index')->with('alert', 'Data berhasil divalidasi');
            }
        }
    }

    public function edit($id)
    {
        $item = PermissionAndSick::findOrFail($id);
        return view('pages.admin.permission-and-sick.edit', compact('item'));
    }
}
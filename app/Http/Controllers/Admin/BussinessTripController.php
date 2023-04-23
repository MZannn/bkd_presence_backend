<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BussinessTrip;
use App\Models\Office;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BussinessTripController extends Controller
{
    public function index(Request $request)
    {
        $offices = Office::all();
        if (Auth::user() && Auth::user()->roles == 'SUPER ADMIN') {
            $items = BussinessTrip::with(['employee', 'office', 'presence'])->paginate(10);
            if ($request->has('search')) {
                $items = Presence::with(['office', 'employee'])->where('employee_id', 'like', '%' . $request->search . '%')->paginate(10);
            }
        } else {
            $items = BussinessTrip::with(['employee', 'office', 'presence'])->where('office_id', Auth::user()->office_id)->paginate(10);
            if ($request->has('search')) {
                $items = Presence::with(['office', 'employee'])->where('employee_id', 'like', '%' . $request->search . '%')->where('office_id', Auth::user()->office_id)->paginate(10);
            }
        }
        return view('pages.admin.bussiness-trip.index', compact('items', 'offices'));
    }
    public function validation(Request $request)
    {

        $data = BussinessTrip::where('office_id', $request->office_id)
            ->where('employee_id', $request->employee_id)
            ->firstOrFail();

        if ($request->status == 'KONFIRMASI') {
            if ($request->start_date == $request->end_date) {
                if ($request->end_time <= '13:30:00') {
                    Presence::findOrFail($request->presence_id)->update([
                        'attendance_clock' => $request->start_time,
                        'presence_date' => $request->start_date,
                        'attendance_entry_status' => "PERJALANAN DINAS",
                    ]);
                } else if ($request->start_time >= '13:30:00' && $request->end_time <= '15:30:00') {
                    Presence::findOrFail($request->presence_id)->update([
                        'attendance_clock_out' => $request->end_time,
                        'presence_date' => $request->end_date,
                        'attendance_exit_status' => "PERJALANAN DINAS",
                    ]);
                } else {
                    $presence = Presence::where('presence_date', $request->start_date)
                        ->where('employee_id', $request->employee_id)
                        ->first();
                    $exists = Presence::where('presence_date', $request->start_date)
                        ->where('attendance_entry_status', "HADIR")
                        ->where('employee_id', $request->employee_id)
                        ->exists();
                    // untuk request 1 hari dan hari kerja
                    if (!$presence && Carbon::parse($request->start_date)->isWeekday() && !$exists) {
                        Presence::create([
                            'employee_id' => $request->employee_id,
                            'office_id' => $request->office_id,
                            'attendance_clock' => $request->start_time,
                            'attendance_clock_out' => $request->end_time,
                            'presence_date' => $request->start_date,
                            'attendance_entry_status' => "PERJALANAN DINAS",
                            'attendance_exit_status' => "PERJALANAN DINAS",
                        ]);
                        dd(!$presence);
                    } else if ($presence && Carbon::parse($request->start_date)->isWeekday() && !$exists) {
                        Presence::findOrFail($request->presence_id)->update([
                            'attendance_clock' => $request->start_time,
                            'attendance_clock_out' => $request->end_time,
                            'presence_date' => $request->start_date,
                            'attendance_entry_status' => "PERJALANAN DINAS",
                            'attendance_exit_status' => "PERJALANAN DINAS",
                        ]);
                        
                    } else if (Carbon::parse($request->start_date)->isWeekend()) {
                        BussinessTrip::findOrFail($data->id)->delete();
                        return redirect()->route('bussinessTrip')->with('alert', 'Data tidak bisa di validasi karena hari libur');
                    } else if ($exists) {
                        BussinessTrip::findOrFail($data->id)->delete();
                        return redirect()->route('bussinessTrip')->with('alert', 'Data tidak bisa di validasi karena sudah ada data presensi');
                    }
                    dd(!$presence && !$exists);
                    BussinessTrip::findOrFail($data->id)->delete();
                    return redirect()->route('bussinessTrip')->with('alert', 'Data berhasil di validasi');
                }
            } else {
                // untuk request lebih dari 1 hari
                $start_date = Carbon::parse($request->start_date);
                $end_date = Carbon::parse($request->end_date);
                $exists = Presence::where('presence_date', '>=', $request->start_date)->where('presence_date', '<=', $request->end_date)->where('attendance_entry_status', "HADIR")->exists();
                for ($date = $start_date; $date <= $end_date; $date->addDay()) {
                    $presence = Presence::where('employee_id', $request->employee_id)->where('presence_date', $date->format('Y-m-d'))->first();
                    if (!$presence && Carbon::parse($date)->isWeekday() && !$exists) {
                        Presence::create([
                            'employee_id' => $request->employee_id,
                            'office_id' => $request->office_id,
                            'attendance_clock' => $request->start_time,
                            'attendance_clock_out' => $request->end_time,
                            'presence_date' => $date->format('Y-m-d'),
                            'attendance_entry_status' => "PERJALANAN DINAS",
                            'attendance_exit_status' => "PERJALANAN DINAS",
                        ]);
                    } else if ($presence && Carbon::parse($date)->isWeekday() && !$exists) {
                        $presence->update([
                            'attendance_clock' => $request->start_time,
                            'attendance_clock_out' => $request->end_time,
                            'attendance_entry_status' => "PERJALANAN DINAS",
                            'attendance_exit_status' => "PERJALANAN DINAS",
                        ]);
                    }
                }
                BussinessTrip::findOrFail($data->id)->delete();
                return redirect()->route('bussinessTrip')->with('alert', 'Data berhasil di validasi');
            }
        } else if ($request->status == 'TOLAK') {
            BussinessTrip::findOrFail($data->id)->delete();
            return redirect()->route('bussinessTrip')->with('alert', 'Permintaan perjalanan dinas ditolak');
        } else {
            return redirect()->route('bussinessTrip')->with('alert', 'Data pending tidak bisa di validasi');
        }

    }

    public function edit($id)
    {
        $offices = Office::all();
        $items = BussinessTrip::with(['employee', 'office', 'presence'])->where('id', $id)->first();
        return view('pages.admin.bussiness-trip.edit', compact('items', 'offices'));
    }
}
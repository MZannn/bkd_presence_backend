<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\Office;
use App\Models\Presence;
use App\Models\Vacation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $offices = Office::all();
        if (Auth::user() && Auth::user()->roles == 'SUPER ADMIN') {
            $items = Vacation::with(['employee', 'office', 'presence'])->paginate(10);
            if ($request->has('search')) {
                $items = Vacation::with(['office', 'employee'])->where('nip', 'like', '%' . $request->search . '%')->paginate(10);
            }
        } else {
            $items = Vacation::with(['employee', 'office', 'presence'])->where('office_id', Auth::user()->office_id)->paginate(10);
            if ($request->has('search')) {
                $items = Vacation::with(['office', 'employee'])->where('nip', 'like', '%' . $request->search . '%')->where('office_id', Auth::user()->office_id)->paginate(10);
            }
        }
        return view('pages.admin.vacation.index', compact('items', 'offices'));
    }
    public function validation(Request $request)
    {

        $data = Vacation::where('office_id', $request->office_id)
            ->where('nip', $request->nip)
            ->firstOrFail();

        if ($request->status == 'KONFIRMASI') {
            if ($request->start_date == $request->end_date) {
                $presence = Presence::where('id', $request->presence_id)
                    ->where('presence_date', $request->start_date)
                    ->where('nip', $request->nip)
                    ->first();
                $exists = Presence::where('presence_date', $request->start_date)
                    ->where('attendance_entry_status', "HADIR")
                    ->where('nip', $request->nip)
                    ->exists();
                // untuk request 1 hari dan hari kerja
                if (!$presence && Carbon::parse($request->start_date)->isWeekday() && !$exists) {
                    Presence::create([
                        'nip' => $request->nip,
                        'office_id' => $request->office_id,
                        'presence_date' => $request->start_date,
                        'attendance_entry_status' => $request->leave_type,
                        'attendance_exit_status' => $request->leave_type,
                    ]);
                } else if ($presence && Carbon::parse($request->start_date)->isWeekday() && !$exists) {
                    Presence::findOrFail($request->presence_id)->update([
                        'presence_date' => $request->start_date,
                        'attendance_entry_status' => $request->leave_type,
                        'attendance_exit_status' => $request->leave_type,
                    ]);

                } else if (Carbon::parse($request->start_date)->isWeekend()) {
                    Vacation::findOrFail($data->id)->delete();
                    return redirect()->route('vacation')->with('alert', 'Data tidak bisa di validasi karena hari libur');
                } else if ($exists) {
                    Vacation::findOrFail($data->id)->delete();
                    return redirect()->route('vacation')->with('alert', 'Data tidak bisa di validasi karena sudah ada data presensi');
                }
                Vacation::findOrFail($data->id)->delete();
                return redirect()->route('vacation')->with('alert', 'Data berhasil di validasi');

            } else {
                // untuk request lebih dari 1 hari
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
                            'attendance_entry_status' => $request->leave_type,
                            'attendance_exit_status' => $request->leave_type,
                        ]);
                    } else if ($presence && Carbon::parse($date)->isWeekday() && !in_array($date->toDateString(), $holidays) && !$exists) {
                        $presence->update([
                            'attendance_entry_status' => $request->leave_type,
                            'attendance_exit_status' => $request->leave_type,
                        ]);
                    }
                }
                Vacation::findOrFail($data->id)->delete();
                return redirect()->route('vacation')->with('alert', 'Data berhasil di validasi');
            }
        } else if ($request->status == 'TOLAK') {
            Vacation::findOrFail($data->id)->delete();
            return redirect()->route('vacation')->with('alert', 'Permintaan Cuti ditolak');
        } else {
            return redirect()->route('vacation')->with('alert', 'Data pending tidak bisa di validasi');
        }

    }

    public function edit($id)
    {
        $item = Vacation::with(['employee', 'office', 'presence'])->findOrFail($id);
        return view('pages.admin.vacation.edit', compact('item'));
    }
}
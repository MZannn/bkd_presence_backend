<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\BussinessTrip;
use App\Models\PermissionAndSick;
use App\Models\Presence;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PresenceController extends Controller
{
    public function all(Request $request)
    {
        $user = Auth::user();
        $presence = Presence::where('nip', $user->nip)->orderBy('presence_date', 'desc')->get();
        return ResponseFormatter::success(
            ['presences' => $presence]
            ,
            'Data Presensi berhasil diambil'
        );
    }

    public function detailPresence(Request $request, $id)
    {
        $user = Auth::user();
        $presence = Presence::where('nip', $user->nip)->where('id', $id)->first();
        return ResponseFormatter::success(
            [
                'detail_presence' => $presence
            ],
            'Data Presensi berhasil diambil'
        );
    }
    public function presenceIn(Request $request, $id)
    {
        $data = $request->all();
        $user = Auth::user();
        $presenceIn = Presence::all()->where('nip', $user->nip)->where('id', $id)->first();
        $presenceIn->update($data);
        $user = Auth::user()->load('office');
        $presence = Presence::where('nip', $user->nip)
            ->orderBy('presence_date', 'desc')
            ->paginate(5);
        return ResponseFormatter::success([
            'user' => $user,
            'presences' => $presence->items()
        ], 'Presensi Masuk berhasil');
    }

    public function presenceOut(Request $request, $id)
    {
        $data = $request->all();
        $user = Auth::user();
        $presenceOut = Presence::all()->where('nip', $user->nip)->where('id', $id)->first();
        $presenceOut->update($data);
        $user = Auth::user()->load('office');
        $presence = Presence::where('nip', $user->nip)
            ->orderBy('presence_date', 'desc')
            ->paginate(5);
        return ResponseFormatter::success([
            'user' => $user,
            'presences' => $presence->items()
        ], 'Presensi Keluar berhasil');
    }

    public function bussinessTrip(Request $request)
    {
        if (BussinessTrip::where('nip', $request->nip)->where('start_date', $request->start_date)->where('end_date', $request->end_date)->exists()) {
            return ResponseFormatter::error([
                'error' => 'Perjalanan dinas sudah diajukan',
            ], 'Perjalanan dinas sudah diajukan', 400);
        }
        if ($request->start_date == $request->end_date) {
            $data = $request->validate([
                'nip' => 'required',
                'office_id' => 'required',
                'presence_id' => 'required',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required',
                'file' => 'required|file|mimes:pdf,jpeg,jpg,png|max:2048',
            ]);
            if ($request->has('file')) {
                $data['file'] = $request->file('file')->store('assets/bussiness_trip', 'public');
                $bussinessTrip = BussinessTrip::create($data);
            }
            return ResponseFormatter::success($bussinessTrip, 'Berhasil mengajukan perjalanan dinas');
        } else {
            $data = $request->validate([
                'nip' => 'required',
                'office_id' => 'required',
                'presence_id' => 'required',
                'start_date' => 'required|date|before:end_date',
                'end_date' => 'required|date|after:start_date',
                'start_time' => 'required',
                'end_time' => 'required',
                'file' => 'required|file|mimes:pdf,jpeg,jpg,png|max:2048',
            ]);

            if ($request->has('file')) {
                $data['file'] = $request->file('file')->store('assets/bussiness_trip', 'public');
                $bussinessTrip = BussinessTrip::create($data);
            }
            return ResponseFormatter::success($bussinessTrip, 'Berhasil mengajukan perjalanan dinas');
        }
    }

    public function permissionAndSick(Request $request)
    {
        try {
            $request->validate([
                'nip' => 'required',
                'office_id' => 'required',
                'presence_id' => 'required',
                'date' => 'required|date',
                'file' => 'required|file|mimes:pdf,jpeg,jpg,png|max:2048',
            ]);

            if (
                PermissionAndSick::where('nip', $request->nip)
                    ->where('date', $request->date)->exists()
            ) {
                return ResponseFormatter::error([
                    'error' => 'Izin atau sakit sudah diajukan',
                ], 'Izin atau sakit sudah diajukan', 400);
            }

            $data = $request->all();

            if ($request->hasFile('file')) {
                $data['file'] = $request->file('file')->store('assets/permission_and_sick', 'public');
            }

            $permissionAndSick = PermissionAndSick::create($data);

            return ResponseFormatter::success($permissionAndSick, 'Berhasil mengajukan izin atau sakit');

        } catch (\Exception $exception) {
            return ResponseFormatter::error([
                'error' => $exception->getMessage(),
            ], 'Terjadi kesalahan', 500);
        }
    }

    public function vacation(Request $request)
    {
        $data = $request->validate([
            'nip' => 'required',
            'office_id' => 'required',
            'presence_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'reason' => 'required',
            'file' => 'required|file|mimes:pdf,jpeg,jpg,png|max:2048',
        ]);

        if (
            Vacation::where('nip', $data['nip'])
                ->where('start_date', $data['start_date'])
                ->where('end_date', $data['end_date'])
                ->exists()
        ) {
            return ResponseFormatter::error([
                'error' => 'Permintaan Cuti sudah diajukan',
            ], 'Permintaan Cuti sudah diajukan', 400);
        }

        if ($data['start_date'] != $data['end_date']) {
            $data = $request->validate([
                'nip' => 'required',
                'office_id' => 'required',
                'presence_id' => 'required',
                'start_date' => 'required|date|before:end_date',
                'end_date' => 'required|date|after:start_date',
                'reason' => 'required',
                'file' => 'required|file|mimes:pdf,jpeg,jpg,png|max:2048',
            ]);
        }

        if ($request->has('file')) {
            $data['file'] = $request->file('file')->store('assets/vacation', 'public');
        }

        $vacation = Vacation::create($data);

        return ResponseFormatter::success($vacation, 'Berhasil mengajukan Cuti');
    }
}
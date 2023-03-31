<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller
{
    public function all(Request $request){
        $data = Presence::all()->where('employee_id', Auth::user()->nip);
        return ResponseFormatter::success($data, 'Data Presensi berhasil diambil');
    }
    public function presenceIn(Request $request, $id)
    {
        $data = $request->all();
        $user = Auth::user();
        $presence = Presence::all()->where('employee_id', $user->nip)->where('id', $id)->first();
        $presence->update($data);
        return ResponseFormatter::success($presence, 'Presensi Masuk berhasil');
    }

    public function presenceOut(Request $request, $id)
    {
        $data = $request->all();
        $user = Auth::user();
        $presence = Presence::all()->where('employee_id', $user->nip)->where('id', $id)->first();
        $presence->update($data);
        return ResponseFormatter::success($presence, 'Presensi Keluar berhasil');
    }
}
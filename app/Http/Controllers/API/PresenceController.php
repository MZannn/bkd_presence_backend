<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Presence;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);

        if ($id) {
            $presence = Presence::find($id);
            if ($presence) {
                return ResponseFormatter::success(
                    $presence,
                    'Data presensi berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data presensi tidak ada',
                    404
                );
            }
        }
        $presence = Presence::query();
        return ResponseFormatter::success(
            $presence->paginate($limit)->items(),
            'Data list presensi berhasil diambil'
        );
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

use App\Mail\SendEmail;
use App\Models\Holiday;
use App\Models\Presence;
use App\Models\ReportChangeDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function fetch(Request $request)
    {
        $user = Auth::user()->load('office');
        $presence = Presence::where('nip', $user->nip)
            ->where('presence_date', Carbon::today())
            ->first();
        $holidays = Holiday::pluck('holiday_date')->toArray();

        if (!$presence && Carbon::today()->isWeekday() && !in_array(Carbon::today()->toDateString(), $holidays) !== false) {
            Presence::create([
                'nip' => $user->nip,
                'office_id' => $user->office_id,
                'presence_date' => Carbon::today()->toDateString(),
            ]);
            $presence = Presence::where('nip', $user->nip)
                ->orderBy('presence_date', 'desc')
                ->paginate(5);
            return ResponseFormatter::success([
                'user' => $user,
                'presences' => $presence->items()
            ], 'Data profile user berhasil diambil');
        }

        $presence = Presence::where('nip', $user->nip)
            ->orderBy('presence_date', 'desc')
            ->paginate(5);
        return ResponseFormatter::success([
            'user' => $user,
            'presences' => $presence->items()
        ], 'Data profile user berhasil diambil');
    }


    public function updateProfile(Request $request)
    {
        try {
            $data = $request->all();
            if ($request->hasFile('profile_photo_path')) {
                $data['profile_photo_path'] = $request->file('profile_photo_path')->store(
                    'assets/employee',
                    'public'
                );
            }
            $user = Auth::user()->load('office');
            $user->update($data);
            $presences = Presence::where('nip', $user->nip)->orderBy('presence_date', 'desc')->get();

            return ResponseFormatter::success([
                'user' => $user,
                'presences' => $presences
            ], 'Profile updated');
        } catch (\Throwable $th) {
            return ResponseFormatter::error(
                [
                    'message' => 'Something went wrong',
                    'error' => $th
                ],
                'Update Profile Failed',
                500
            );
        }
    }
    public function reportChangeDevice(Request $request)
    {
        if (ReportChangeDevice::where('nip', $request->nip)->exists()) {
            return ResponseFormatter::error([
                'error' => 'Laporan perubahan device sudah diajukan',
            ], 'Laporan perubahan device sudah diajukan', 400);
        }
        $data = $request->validate([
            'nip' => 'required',
            'office_id' => 'required',
            'reason' => 'required',
        ]);
        $reportChangeDevice = ReportChangeDevice::create($data);
        $adminEmail = User::where('office_id', Auth::user()->office_id)->first();
        if ($adminEmail == null) {
            $adminEmail = User::where('roles', 'SUPER ADMIN')->first();
            Mail::to($adminEmail['email'])->send(new SendEmail());
        } else {
            Mail::to($adminEmail['email'])->send(new SendEmail());
            $superAdminEmail = User::where('roles', 'SUPER ADMIN')->first();
            Mail::to($superAdminEmail['email'])->send(new SendEmail());
        }
        return ResponseFormatter::success($reportChangeDevice, 'Berhasil mengajukan laporan perubahan device');
    }

}
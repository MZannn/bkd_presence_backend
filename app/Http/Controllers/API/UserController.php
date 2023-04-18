<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

use App\Mail\SendEmail;
use App\Models\Presence;
use App\Models\ReportChangeDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Grei\TanggalMerah;

class UserController extends Controller
{
    public function fetch(Request $request)
    {
        $user = Auth::user()->load('office');
        $presence = Presence::where('employee_id', $user->nip)
            ->where('presence_date', Carbon::now()->format('Y-m-d'))
            ->first();
        $isHoliday = new TanggalMerah();
        $isHoliday->set_date(Carbon::now()->format('Ymd'));
        $isHoliday = $isHoliday->is_holiday();
        dd(!$presence && Carbon::now()->format('l') != 'Saturday' && Carbon::now()->format('l') != 'Sunday' && !$isHoliday);
        if (!$presence && Carbon::now()->format('l') != 'Saturday' && Carbon::now()->format('l') != 'Sunday' && !$isHoliday) {
            Presence::create([
                'employee_id' => $user->nip,
                'office_id' => $user->office_id,
                'presence_date' => Carbon::now()->format('Y-m-d'),
            ]);
            $presence = Presence::where('employee_id', $user->nip)
                ->orderBy('presence_date', 'desc')
                ->paginate(5);
            return ResponseFormatter::success([
                'user' => $user,
                'presences' => $presence->items()
            ], 'Data profile user berhasil diambil');
        }
        $presence = Presence::where('employee_id', $user->nip)
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
            $presences = Presence::where('employee_id', $user->nip)->orderBy('presence_date', 'desc')->get();

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
    // update photo
    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), ['file' => 'required|image|max:2048']);

        if ($validator->fails()) {
            return ResponseFormatter::error([
                'error' => $validator->errors(),
            ], 'Upload photo fails', 400);
        }

        if ($request->file('file')) {
            $file = $request->file->store('assets/user', 'public');

            $user = Auth::user();
            $user->profile_photo_path = $file;
            $user->update();
            return ResponseFormatter::success([$file], 'File successfully Uploaded');
        }
    }

    public function reportChangeDevice(Request $request)
    {
        if (ReportChangeDevice::where('employee_id', $request->employee_id)->exists()) {
            return ResponseFormatter::error([
                'error' => 'Laporan perubahan device sudah diajukan',
            ], 'Laporan perubahan device sudah diajukan', 400);
        }
        $data = $request->validate([
            'employee_id' => 'required',
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
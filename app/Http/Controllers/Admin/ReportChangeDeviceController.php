<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ReportChangeDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client;

class ReportChangeDeviceController extends Controller
{
    public function index()
    {
        if (Auth::user()->roles == 'SUPER ADMIN') {
            $items = ReportChangeDevice::with(['office', 'employee'])->paginate(10);
        } else {
            $items = ReportChangeDevice::with(['office', 'employee'])->where('office_id', Auth::user()->office_id)->paginate(10);
        }
        return view('pages.admin.report-change-device.index', compact('items'));
    }

    public function approved(Request $request)
    {

        $sid = config('twilio.account_sid');
        $token = config('twilio.auth_token');
        $from = config('twilio.from');
        $client = new Client($sid, $token);
        if ($request->status == 'APPROVED') {
            $employee = Employee::where('nip', $request->employee_id)->first();
            $phone_number = $employee->phone_number;

            if (substr($phone_number, 0, 2) === '08') {
                $phone_number = '+62' . substr($phone_number, 1);
            }
            dd("whatsapp:$phone_number");
            // $employee->update([
            //     'device_id' => null
            // ]);
            // $message = $client->messages
            //     ->create(
            //         "whatsapp:$phone_number",
            //         // to
            //         [
            //             "from" => "whatsapp:$from",
            //             "body" => "Permintaan Penggantian Device Anda Telah Disetujui"
            //         ]
            //     );

            // dd($message);

            ReportChangeDevice::findOrFail($request->id)->delete();
            return redirect()->route('reportChangeDevice')->with('alert', 'Berhasil Menyetujui Permintaan');
        } else if ($request->status == 'REJECTED') {
            ReportChangeDevice::findOrFail($request->id)->delete();
            return redirect()->route('reportChangeDevice')->with('alert', 'Berhasil Menolak Permintaan');
        } else {
            return redirect()->route('reportChangeDevice')->with('alert', 'Gagal Menyetujui Permintaan');
        }

    }
}
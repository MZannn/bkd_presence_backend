<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\ReportChangeDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if ($request->status == 'APPROVED') {
            Employee::where('nip', $request->employee_id)->update([
                'device_id' => null
            ]);
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
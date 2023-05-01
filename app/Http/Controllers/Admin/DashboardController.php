<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BussinessTrip;
use App\Models\Employee;
use App\Models\PermissionAndSick;
use App\Models\ReportChangeDevice;
use App\Models\Vacation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->roles == 'SUPER ADMIN') {
            $employee = Employee::all();
            $bussinessTrip = BussinessTrip::all();
            $permissionAndSick = PermissionAndSick::all();
            $reportChangeDevice = ReportChangeDevice::all();
            $vacation = Vacation::all();
            
        } else {
            $employee = Employee::with(['office'])->where('office_id', Auth::user()->office_id)->get();
            $bussinessTrip = BussinessTrip::with(['office', 'employee'])->where('office_id', Auth::user()->office_id)->get();
            $permissionAndSick = PermissionAndSick::with(['office', 'employee'])->where('office_id', Auth::user()->office_id)->get();
            $vacation = Vacation::with(['office', 'employee'])->where('office_id', Auth::user()->office_id)->get();
            $reportChangeDevice = ReportChangeDevice::with(['office', 'employee'])->where('office_id', Auth::user()->office_id)->get();
        }
        return view('pages.admin.dashboard', compact('employee', 'bussinessTrip','permissionAndSick', 'reportChangeDevice'));
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PresenceExport;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Office;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $offices = Office::all();
        if (Auth::user() && Auth::user()->roles == 'SUPER ADMIN') {
            if ($request->has('search') && $request->start_date != null && $request->end_date != null) {
                if ($request->start_date == $request->end_date) {
                    $items = Presence::with(['office', 'employee'])->where('employee_id', 'like', '%' . $request->search . '%')->whereBetween('presence_date', [$request->start_date, $request->end_date])->paginate(10);
                    return view('pages.admin.presence.index', compact('items', 'offices'));
                }
                $request->validate([
                    'start_date' => 'required|date|before:end_date',
                    'end_date' => 'required|date|after:start_date'
                ]);
                $items = Presence::with(['office', 'employee'])->where('employee_id', 'like', '%' . $request->search . '%')->whereBetween('presence_date', [$request->start_date, $request->end_date])->paginate(10);
                return view('pages.admin.presence.index', compact('items', 'offices'));
            } else if ($request->has('search')) {
                $items = Presence::with(['office', 'employee'])->where('employee_id', 'like', '%' . $request->search . '%')->paginate(10);
                return view('pages.admin.presence.index', compact('items', 'offices'));
            } else if ($request->has('start_date') && $request->has('end_date')) {
                if ($request->start_date == $request->end_date) {
                    $items = Presence::with(['office', 'employee'])->whereBetween('presence_date', [$request->start_date, $request->end_date])->paginate(10);
                    return view('pages.admin.presence.index', compact('items', 'offices'));
                }
                $request->validate([
                    'start_date' => 'required|date|before:end_date',
                    'end_date' => 'required|date|after:start_date'
                ]);
                $items = Presence::with(['office', 'employee'])->whereBetween('presence_date', [$request->start_date, $request->end_date])->paginate(10);
                return view('pages.admin.presence.index', compact('items', 'offices'));
            } else if ($request->office_id == null) {
                $data = Presence::with(['office', 'employee']);
                if ($data->first() != null) {
                    $items = Presence::with(['office', 'employee'])->where('office_id', $data->first()->office->id)->paginate(10);
                    return view('pages.admin.presence.index', compact('items', 'offices'));
                }
            } else {
                $items = Presence::with(['office', 'employee'])->where('office_id', $request->office_id)->orderBy('presence_date')->paginate(10);
                return view('pages.admin.presence.index', compact('items', 'offices'));
            }
            $items = Presence::with(['office', 'employee'])->orderBy('presence_date')->paginate(10);
            return view('pages.admin.presence.index', compact('items', 'offices'));
        } else {
            if ($request->has('search') && $request->start_date != null && $request->end_date != null) {
                if ($request->start_date == $request->end_date) {
                    $items = Presence::with(['office', 'employee'])->where('employee_id', 'like', '%' . $request->search . '%')->whereBetween('presence_date', [$request->start_date, $request->end_date])->paginate(10);
                    return view('pages.admin.presence.index', compact('items', 'offices'));
                }
                $request->validate([
                    'start_date' => 'required|date|before:end_date',
                    'end_date' => 'required|date|after:start_date'
                ]);
                $items = Presence::with(['office', 'employee'])->where('employee_id', 'like', '%' . $request->search . '%')->whereBetween('presence_date', [$request->start_date, $request->end_date])->paginate(10);
                return view('pages.admin.presence.index', compact('items', 'offices'));
            } else if ($request->has('search')) {
                $items = Presence::with(['office', 'employee'])->where('employee_id', 'like', '%' . $request->search . '%')->paginate(10);
                return view('pages.admin.presence.index', compact('items', 'offices'));
            } else if ($request->has('start_date') && $request->has('end_date')) {
                if ($request->start_date == $request->end_date) {
                    $items = Presence::with(['office', 'employee'])->whereBetween('presence_date', [$request->start_date, $request->end_date])->paginate(10);
                    return view('pages.admin.presence.index', compact('items', 'offices'));
                }
                $request->validate([
                    'start_date' => 'required|date|before:end_date',
                    'end_date' => 'required|date|after:start_date'
                ]);
                $items = Presence::with(['office', 'employee'])->whereBetween('presence_date', [$request->start_date, $request->end_date])->paginate(10);
                return view('pages.admin.presence.index', compact('items', 'offices'));
            }
        }
        $items = Presence::with('employee')->orderBy('presence_date')->paginate(10);
        return view('pages.admin.presence.index', compact('items', 'offices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Presence::with(['employee', 'office'])->get();
        $offices = Office::all();
        return view('pages.admin.presence.create', compact('items', 'offices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $items = Presence::with(['employee', 'office'])->where('id', $id)->get();

        return view('pages.admin.presence.detail', compact('items'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'office_id' => 'required'
        ]);
        $office_id = $request->input('office_id');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $date = Carbon::parse($start_date);
        $month = $date->format('F');
        return Excel::download(new PresenceExport($office_id, $start_date, $end_date), 'rekapan presensi - ' . $month . ' ' . $date->format('Y') . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
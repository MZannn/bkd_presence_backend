<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Presence;
use App\Models\Vacation;
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
                $items = Presence::with(['office', 'employee'])->where('employee_id', 'like', '%' . $request->search . '%')->paginate(10);
            }
        } else {
            $items = Vacation::with(['employee', 'office', 'presence'])->where('office_id', Auth::user()->office_id)->paginate(10);
            if ($request->has('search')) {
                $items = Presence::with(['office', 'employee'])->where('employee_id', 'like', '%' . $request->search . '%')->where('office_id', Auth::user()->office_id)->paginate(10);
            }
        }
        return view('pages.admin.bussiness-trip.index', compact('items', 'offices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Vacation $vacation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vacation $vacation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vacation $vacation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vacation $vacation)
    {
        //
    }
}
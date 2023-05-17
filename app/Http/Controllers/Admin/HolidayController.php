<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Carbon\Carbon;
use Grei\TanggalMerah;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Holiday::all();
        return view('pages.admin.holiday.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.admin.holiday.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Holiday::create([
            'holiday_date' => $request->holiday_date,
        ]);
        return redirect()->route('holiday.index')->with('alert', 'Data berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Holiday::findOrFail($id);
        return view('pages.admin.holiday.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Holiday::findOrFail($id)->update([
            'holiday_date' => $request->holiday_date,
        ]);
        return redirect()->route('holiday.index')->with('alert', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Holiday::findOrFail($id)->delete();
        return redirect()->route('holiday.index')->with('alert', 'Data berhasil dihapus');
    }

    public function toScrape()
    {
        return view('pages.admin.holiday.scrape');
    }

    public function scrapingHolidayDate(Request $request)
    {
        $start_date = Carbon::parse($request->start_date);
        $end_date = Carbon::parse($request->end_date);
        $isHoliday = new TanggalMerah();

        for ($date = $start_date; $date <= $end_date; $date->addDay()) {
            $exists = Holiday::where('holiday_date', '=', $date)->exists();
            $isHoliday->set_date($date->toDateString());
            if ($isHoliday->is_holiday() && !$exists) {
                Holiday::create([
                    'holiday_date' => $date->toDateString(),
                ]);
            }
        }
        return redirect()->route('holiday.index')->with('alert', 'Data berhasil ditambahkan');
    }

}
<?php

namespace App\Exports;

use App\Models\Presence;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Grei\TanggalMerah;

class PresenceExport implements FromView
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function view(): View
    {
        $start_date = $this->request->start_date;
        $end_date = $this->request->end_date;
        // Mengambil data presensi dari database
        $presence = Presence::with(['office', 'employee'])
            ->whereBetween('presence_date', [$start_date, $end_date])
            ->get();


        $calculator = new TanggalMerah();

        $working_days = 0;
        $current_date = Carbon::parse($start_date);
        while ($current_date->lte(Carbon::parse($end_date))) {
            if (!$current_date->is_holiday() && $current_date->isWeekday()) {
                $working_days++;
            }
            $current_date->addDay();
        }

        $total_working_days = $presence->filter(function ($p) use ($calculator) {
            return !$calculator->is_holiday($p->presence_date) && Carbon::parse($p->presence_date)->isWeekday();
        })->count();
        
        return view('pages.admin.presence.export', [
            'items' => $presence,
            'working_days' => $working_days,
            'total_working_days' => $total_working_days,
        ]);
    }

}
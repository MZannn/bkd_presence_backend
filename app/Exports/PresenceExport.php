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
        $total_days = Carbon::parse($start_date)->diffInDays(Carbon::parse($end_date));
        for ($i = 0; $i <= $total_days; $i++) {
            $current_date = Carbon::parse($start_date)->addDays($i);
            if (!$calculator->is_holiday($current_date)) {
                $working_days++;
            }
        }

        $total_working_days = 0;
        foreach ($presence as $p) {
            if (!$calculator->is_holiday($p->presence_date)) {
                $total_working_days++;
            }
        }
        return view('pages.admin.presence.export', [
            'items' => $presence,
            'working_days' => $working_days,
            'total_working_days' => $total_working_days,
        ]);
    }

}
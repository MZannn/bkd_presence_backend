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
        $presences = Presence::with(['office', 'employee'])
            ->whereBetween('presence_date', [$start_date, $end_date])
            ->get();

        // Menghitung jumlah hari kerja
        $calculator = new TanggalMerah();
        $tz = new DateTimeZone('Asia/Jakarta');
        $calculator->set_timezone($tz);

        $working_days = 0;
        $attendance_counts = [];
        $current_date = Carbon::parse($start_date);
        while ($current_date->lte(Carbon::parse($end_date))) {
            $calculator->set_date($current_date->toDateString());
            if (!$calculator->is_holiday() && $current_date->isWeekday()) {
                $working_days++;
                $attendance_counts[$current_date->toDateString()] = 0;
            }
            $current_date->addDay();
        }

        foreach ($presences as $presence) {
            $date = Carbon::parse($presence->presence_date)->toDateString();
            if (isset($attendance_counts[$date]) && $presence->attendance_entry_status === 'HADIR' && $presence->attendance_exit_status === 'HADIR') {
                $attendance_counts[$date]++;
            }
        }

        return view('pages.admin.presence.export', [
            'items' => $presences,
            'attendance_counts' => $attendance_counts,
            'working_days' => $working_days,
        ]);
    }


}
<?php
namespace App\Exports;

use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Grei\TanggalMerah;

class PresenceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $nip;
    protected $start_date;
    protected $end_date;

    public function __construct($nip, $start_date, $end_date)
    {
        $this->nip = $nip;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection(): Collection
    {
        // Mengambil data presensi dari database
        $presences = Presence::with(['office', 'employee'])
            ->where('nip', $this->nip)
            ->whereBetween('presence_date', [$this->start_date, $this->end_date])
            ->get();

        // Menghitung jumlah hari kerja
        $calculator = new TanggalMerah();
        $working_days = 0;
        $attendance_counts = [
            'hadir' => 0,
            'izin' => 0,
            'sakit' => 0,
            'tidak_hadir' => 0,
            'terlambat' => 0,
        ];
        $current_date = Carbon::parse($this->start_date);
        while ($current_date->lte(Carbon::parse($this->end_date))) {
            $calculator->set_date($current_date->toDateString());
            if (!$calculator->is_holiday() && $current_date->isWeekday()) {
                $working_days++;
            }
            $current_date->addDay();
        }

        foreach ($presences as $presence) {
            if ($presence->attendance_entry_status === 'HADIR' && $presence->attendance_exit_status === 'HADIR') {
                $attendance_counts['hadir']++;
            } elseif ($presence->attendance_entry_status === 'IZIN' || $presence->attendance_exit_status === 'IZIN') {
                $attendance_counts['izin']++;
            } elseif ($presence->attendance_entry_status === 'SAKIT' || $presence->attendance_exit_status === 'SAKIT') {
                $attendance_counts['sakit']++;
            } elseif ($presence->attendance_entry_status === 'TIDAK HADIR' || $presence->attendance_exit_status === 'TIDAK HADIR') {
                $attendance_counts['tidak_hadir']++;
            } elseif ($presence->attendance_entry_status === 'TERLAMBAT' || $presence->attendance_exit_status === 'TERLAMBAT') {
                $attendance_counts['terlambat']++;
            }
        }

        return collect([
            [
                'nip' => $this->nip,
                'nama' => $presences->first()->employee->name,
                'kantor' => $presences->first()->office->name,
                'hari_kerja' => $working_days,
                'hadir' => $attendance_counts['hadir'],
                'izin' => $attendance_counts['izin'],
                'sakit' => $attendance_counts['sakit'],
                'tidak_hadir' => $attendance_counts['tidak_hadir'],
                'terlambat' => $attendance_counts['terlambat'],
            ]
        ]);
    }
    public function headings(): array
    {
        return [
            'NIP',
            'Nama',
            'Kantor',
            'Hari Kerja',
            'Hadir',
            'Izin',
            'Sakit',
            'Tidak Hadir',
            'Terlambat'
        ];
    }

    public function map($row): array
    {
        return [
            $row['nip'],
            $row['nama'],
            $row['kantor'],
            $row['hari_kerja'],
            $row['hadir'],
            $row['izin'],
            $row['sakit'],
            $row['tidak_hadir'],
            $row['terlambat'],
        ];
    }
}
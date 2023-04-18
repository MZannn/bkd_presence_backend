<?php
// PresenceExport.php
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
    protected $start_date;
    protected $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection(): Collection
    {
        // Mengambil data presensi dan pegawai dari database
        $presences = Presence::with(['office', 'employee'])
            ->whereBetween('presence_date', [$this->start_date, $this->end_date])
            ->join('employees', 'employees.nip', '=', 'presences.employee_id')
            ->select('presences.*', 'employees.nip')
            ->orderBy('employees.nip')
            ->get();

        // Menghitung jumlah hari kerja
        $calculator = new TanggalMerah();
        $attendance_counts = [];
        $working_days = 0;
        $current_date = Carbon::parse($this->start_date);
        while ($current_date->lte(Carbon::parse($this->end_date))) {
            $calculator->set_date($current_date->toDateString());
            if (!$calculator->is_holiday() && $current_date->isWeekday()) {
                $working_days++;
            }
            $current_date->addDay();
        }
        foreach ($presences as $presence) {
            $nip = $presence->employee->nip;

            if (!isset($attendance_counts[$nip])) {

                $attendance_counts[$nip] = [
                    'nip' => sprintf('%019s', $nip),
                    'nama' => $presence->employee->name,
                    'kantor' => $presence->office->name,
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'tidak_hadir' => 0,
                    'terlambat' => 0,
                    'persentase_kehadiran' => 0,
                ];
            }

            // $calculator->set_date($presence->presence_date);
            // if (!$calculator->is_holiday() && Carbon::parse($presence->presence_date)->isWeekday()) {
            //     $working_days++;
            // }

            if (strtoupper($presence->attendance_entry_status) === 'HADIR' && strtoupper($presence->attendance_exit_status) === 'HADIR') {
                $attendance_counts[$nip]['hadir']++;
            } elseif (strtoupper($presence->attendance_entry_status) === 'IZIN' || strtoupper($presence->attendance_exit_status) === 'IZIN') {
                $attendance_counts[$nip]['izin']++;
            } elseif (strtoupper($presence->attendance_entry_status) === 'SAKIT' || strtoupper($presence->attendance_exit_status) === 'SAKIT') {
                $attendance_counts[$nip]['sakit']++;
            } elseif (strtoupper($presence->attendance_entry_status) == null || strtoupper($presence->attendance_exit_status) == null) {
                $attendance_counts[$nip]['tidak_hadir']++;
            } elseif (strtoupper($presence->attendance_entry_status) === 'TERLAMBAT' || strtoupper($presence->attendance_exit_status) === 'TERLAMBAT') {
                $attendance_counts[$nip]['hadir']++;
                $attendance_counts[$nip]['terlambat']++;
            }

            $attendance_counts[$nip]['hari_kerja'] = $working_days;
            $attendance_counts[$nip]['persentase_kehadiran'] = ($attendance_counts[$nip]['hadir'] / $working_days) * 100;
        }

        return collect(array_values($attendance_counts));
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
            'Terlambat',
            'Persentase Kehadiran',
        ];
    }

    public function map($row): array
    {
        return [
            "'" . strval($row['nip']),
            $row['nama'],
            $row['kantor'],
            $row['hari_kerja'],
            $row['hadir'],
            $row['izin'],
            $row['sakit'],
            $row['tidak_hadir'],
            $row['terlambat'],
            $row['persentase_kehadiran'],
        ];
    }


    public function columnFormats(): array
    {
        return [
            'A' => '0000000000000000000',
        ];
    }
}
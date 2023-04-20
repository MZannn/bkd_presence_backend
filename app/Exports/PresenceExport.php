<?php
// PresenceExport.php
namespace App\Exports;

use App\Models\Employee;
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
        $employees = Employee::with('office')
            ->orderBy('nip')
            ->get();
        // Menghitung jumlah hari kerja
        $calculator = new TanggalMerah();
        $attendance_counts = [];
        $working_days = 0;
        $current_date = Carbon::parse($this->start_date);
        $total_late = 0;
        while ($current_date->lte(Carbon::parse($this->end_date))) {
            $calculator->set_date($current_date->toDateString());
            if (!$calculator->is_holiday() && $current_date->isWeekday()) {
                $working_days++;
            }
            $current_date->addDay();
        }
        foreach ($employees as $employee) {


            $nip = $employee->nip;

            if (!isset($attendance_counts[$nip])) {

                $attendance_counts[$nip] = [
                    'nip' => sprintf('%019s', $nip),
                    'nama' => $employee->name,
                    'kantor' => $employee->office->name,
                    'hari_kerja' => 0,
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'tidak_hadir' => 0,
                    'terlambat' => 0,
                    'total_terlambat_dalam_menit' => 0,
                    'persentase_kehadiran' => 0,
                ];
            }

            foreach ($presences as $presence) {
                $attendance_date = Carbon::parse($this->start_date);
                while ($attendance_date->lte(Carbon::parse($this->end_date))) {
                    if ($presence->employee_id === $nip) {
                        $presence_date = Carbon::parse($presence->presence_date);
                        if ($attendance_date->isSameDay($presence_date)) {
                            $today = Presence::where('employee_id', $nip)
                                ->where('presence_date', $attendance_date)
                                ->first();
                            if (!$today) {
                                $attendance_counts[$nip]['tidak_hadir']++;
                            } else if (strtoupper($presence->attendance_entry_status) === 'HADIR' && strtoupper($presence->attendance_exit_status) === 'HADIR') {
                                $attendance_counts[$nip]['hadir']++;
                            } elseif (strtoupper($presence->attendance_entry_status) === 'IZIN' || strtoupper($presence->attendance_exit_status) === 'IZIN') {
                                $attendance_counts[$nip]['izin']++;
                            } elseif (strtoupper($presence->attendance_entry_status) === 'SAKIT' || strtoupper($presence->attendance_exit_status) === 'SAKIT') {
                                $attendance_counts[$nip]['sakit']++;
                            } elseif (strtoupper($presence->attendance_entry_status) === 'TERLAMBAT' && strtoupper($presence->attendance_exit_status) === 'HADIR') {
                                $attendance_counts[$nip]['hadir']++;
                                $attendance_counts[$nip]['terlambat']++;
                                $entry_time = Carbon::parse($presence->attendance_clock);
                                $entry_limit = Carbon::now()->setTime(8, 0, 0);
                                // Jika waktu masuk terlambat
                                if ($entry_time->isAfter($entry_limit)) {
                                    $late_duration = $entry_time->diffInMinutes($entry_limit);
                                    $total_late += $late_duration;
                                }
                            } elseif (strtoupper($presence->attendance_entry_status) == null && strtoupper($presence->attendance_exit_status) == null) {
                                $attendance_counts[$nip]['tidak_hadir']++;
                            } else {
                                $attendance_counts[$nip]['tidak_hadir']++;
                            }
                        }
                    }
                    $attendance_date->addDay();
                }
                $attendance_counts[$nip]['hari_kerja'] = $working_days;
                $attendance_counts[$nip]['total_terlambat_dalam_menit'] = $total_late;
                $attendance_counts[$nip]['persentase_kehadiran'] = ($attendance_counts[$nip]['hadir'] / $working_days) * 100;
            }
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
            'Terlambat Dalam Menit',
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
            $row['total_terlambat_dalam_menit'],
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
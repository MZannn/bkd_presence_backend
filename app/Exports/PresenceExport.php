<?php
// PresenceExport.php
namespace App\Exports;

use App\Models\Presence;
use App\Models\Employee;
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
        // Mengambil data pegawai dari database
        $employees = Employee::all();

        // Menghitung jumlah hari kerja
        $calculator = new TanggalMerah();
        $working_days = 0;
        $current_date = Carbon::parse($this->start_date);
        while ($current_date->lte(Carbon::parse($this->end_date))) {
            $calculator->set_date($current_date->toDateString());
            if (!$calculator->is_holiday() && $current_date->isWeekday()) {
                $working_days++;
            }
            $current_date->addDay();
        }

        $data = [];
        foreach ($employees as $employee) {
            // Mengambil data presensi dari database untuk setiap pegawai
            $presences = Presence::with(['office'])
                ->where('employee_id', $employee->id)
                ->whereBetween('presence_date', [$this->start_date, $this->end_date])
                ->get();

            $attendance_counts = [
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'tidak_hadir' => 0,
                'terlambat' => 0,
            ];

            foreach ($presences as $presence) {
                if ($presence->attendance_entry_status === 'HADIR' || strtoupper($presence->attendance_entry_status) === 'TERLAMBAT' && $presence->attendance_exit_status === 'HADIR') {
                    $attendance_counts['hadir']++;
                } elseif ($presence->attendance_entry_status === 'IZIN' || $presence->attendance_exit_status === 'IZIN') {
                    $attendance_counts['izin']++;
                } elseif ($presence->attendance_entry_status === 'SAKIT' || $presence->attendance_exit_status === 'SAKIT') {
                    $attendance_counts['sakit']++;
                } elseif ($presence->attendance_entry_status == null || $presence->attendance_exit_status == null) {
                    $attendance_counts['tidak_hadir']++;
                } elseif (strtoupper($presence->attendance_entry_status) === 'TERLAMBAT' || strtoupper($presence->attendance_exit_status) === 'TERLAMBAT') {
                    $attendance_counts['terlambat']++;
                }
            }

            $data[] = [
                'nip' => $employee->nip,
                'nama' => $employee->name,
                'kantor' => $presences->isNotEmpty() ? $presences[0]->office->name : '-',
                'hari_kerja' => $working_days,
                'hadir' => $attendance_counts['hadir'],
                'izin' => $attendance_counts['izin'],
                'sakit' => $attendance_counts['sakit'],
                'tidak_hadir' => $attendance_counts['tidak_hadir'],
                'terlambat' => $attendance_counts['terlambat'],
                'persentase_kehadiran' => $working_days > 0 ? round($attendance_counts['hadir'] / $working_days * 100, 2) : 0,
            ];
        }
    
        return collect($data);
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
            $row['nip'],
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
}    
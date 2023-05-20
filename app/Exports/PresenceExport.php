<?php
// PresenceExport.php
namespace App\Exports;

use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Office;
use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PresenceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $start_date;
    protected $end_date;
    protected $office_id;

    public function __construct($office_id, $start_date, $end_date)
    {
        $this->office_id = $office_id;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection(): Collection
    {
        // Mengambil data presensi dan pegawai dari database
        $presences = Presence::with(['office', 'employee'])
            ->where('presences.office_id', $this->office_id)
            ->whereBetween('presence_date', [$this->start_date, $this->end_date])
            ->join('employees', 'employees.nip', '=', 'presences.nip')
            ->select('presences.*', 'employees.nip')
            ->orderBy('employees.nip')
            ->get();
        $employees = Employee::with('office')
            ->where('office_id', $this->office_id)
            ->orderBy('nip')
            ->get();
        // Menghitung jumlah hari kerja
        $attendance_counts = [];
        $working_days = 0;
        $current_date = Carbon::parse($this->start_date);
        $total_late = 0;
        $holidays = Holiday::pluck('holiday_date')->toArray();
        while ($current_date->lte(Carbon::parse($this->end_date))) {
            // Memeriksa apakah tanggal saat ini bukan hari libur dan merupakan hari kerja (hari biasa)
            if (!in_array($current_date->toDateString(), $holidays) && $current_date->isWeekday()) {
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
                    'izin_atau_sakit' => 0,
                    'perjalanan_dinas' => 0,
                    'cuti' => 0,
                    'jenis_cuti' => [],
                    'tidak_hadir' => 0,
                    'terlambat' => 0,
                    'total_terlambat_dalam_menit' => 0,
                    'keterangan_cuti' => [],
                ];
            }

            foreach ($presences as $presence) {
                $attendance_date = Carbon::parse($this->start_date);
                for ($attendance_date; $attendance_date < Carbon::parse($this->end_date); $attendance_date->addDay()) {
                    if ($presence->nip === $nip) {
                        $presence_date = Carbon::parse($presence->presence_date);
                        if ($attendance_date->eq($presence_date)) {
                            if (strtoupper($presence->attendance_entry_status) === 'HADIR' && strtoupper($presence->attendance_exit_status) === 'HADIR') {
                                $attendance_counts[$nip]['hadir']++;
                            } elseif (strtoupper($presence->attendance_entry_status) === 'IZIN' && strtoupper($presence->attendance_exit_status) === 'IZIN' || strtoupper($presence->attendance_entry_status) === 'SAKIT' || strtoupper($presence->attendance_exit_status) === 'SAKIT') {
                                $attendance_counts[$nip]['izin_atau_sakit']++;
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
                            } elseif (strtoupper($presence->attendance_entry_status) === 'PERJALANAN DINAS' || strtoupper($presence->attendance_exit_status) === 'PERJALANAN DINAS') {
                                $attendance_counts[$nip]['perjalanan_dinas']++;
                            } elseif (stripos(strtoupper($presence->attendance_entry_status), 'CUTI') !== false && stripos(strtoupper($presence->attendance_exit_status), 'CUTI') !== false) {
                                $attendance_counts[$nip]['cuti']++;
                                $jenis_cuti = $presence->attendance_entry_status;
                                if (!isset($attendance_counts[$nip]['jenis_cuti'][$jenis_cuti])) {
                                    $attendance_counts[$nip]['jenis_cuti'][$jenis_cuti] = 0;
                                }
                                $attendance_counts[$nip]['jenis_cuti'][$jenis_cuti]++;

                                $durasi_cuti = Carbon::parse($presence->presence_date)->diffInDays($presence->end_date);
                                $keterangan_cuti = $presence->attendance_entry_status . ' selama ' . $durasi_cuti . ' hari';
                                if (!in_array($keterangan_cuti, $attendance_counts[$nip]['keterangan_cuti'])) {
                                    $attendance_counts[$nip]['keterangan_cuti'][] = $keterangan_cuti;
                                }
                            }
                        }
                    }
                }
                $attendance_counts[$nip]['tidak_hadir'] = $working_days - $attendance_counts[$nip]['hadir'];
                $attendance_counts[$nip]['hari_kerja'] = $working_days;
                $attendance_counts[$nip]['total_terlambat_dalam_menit'] = $total_late;
            }
        }

        return collect(array_values($attendance_counts));
    }

    public function headings(): array
    {
        $office = Office::findOrFail($this->office_id);
        $start_date = Carbon::parse($this->start_date)->format('d-m-Y');
        $end_date = Carbon::parse($this->end_date)->format('d-m-Y');
        return [
            ['Kantor:', $office->name],
            ['Periode:', $start_date . ' s/d ' . $end_date],
            [''],
            [
                'NIP',
                'Nama',
                'Kantor',
                'Hari Kerja',
                'Hadir',
                'Izin Atau Sakit',
                'Perjalanan Dinas',
                'Cuti',
                'Keterangan Cuti',
                'Tidak Hadir',
                'Terlambat',
                'Terlambat Dalam Menit',
            ]
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
            $row['izin_atau_sakit'],
            $row['perjalanan_dinas'],
            $row['cuti'],
            implode(', ', $row['keterangan_cuti']),
            $row['tidak_hadir'],
            $row['terlambat'],
            $row['total_terlambat_dalam_menit'],
        ];
    }


    public function columnFormats(): array
    {
        return [
            'A' => '0000000000000000000',
        ];
    }
}
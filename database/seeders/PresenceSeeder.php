<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PresenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nip = '2001081320230408079';
        $office_id = 1; 
    
        $date = Carbon::create(2023, 5, 2, 0, 0, 0); // Tanggal awal
    
        // Buat 10 data kehadiran
        for ($i = 1; $i <= 10; $i++) {
            // Jika hari Sabtu, lewati
            if ($date->isSaturday() || $date->isSunday()) {
                $date->addDay(); // Tambahkan 1 hari ke depan
                continue;
            }
    
            $clock = $date->copy()->addHours(mt_rand(7, 7))->addMinutes(mt_rand(0, 59))->addSeconds(mt_rand(0, 59)); // Jam datang antara 07:00:00 - 09:59:59
            $clock_out = $date->copy()->addHours(mt_rand(15, 19))->addMinutes(mt_rand(0, 59))->addSeconds(mt_rand(0, 59)); // Jam pulang antara 15:00:00 - 19:59:59
            $entry_position = 'Entrance Position ' . $i;
            $entry_distance = mt_rand(0, 50) / 1000;
            $exit_position = 'Exit Position ' . $i;
            $exit_distance = mt_rand(0, 50) / 1000;
    
            DB::table('presences')->insert([
                'nip' => $nip,
                'office_id' => $office_id,
                'attendance_clock' => $clock,
                'attendance_clock_out' => $clock_out,
                'presence_date' => $date->format('Y-m-d'),
                'attendance_entry_status' => 'HADIR',
                'attendance_exit_status' => 'HADIR',
                'entry_position' => $entry_position,
                'entry_distance' => $entry_distance,
                'exit_position' => $exit_position,
                'exit_distance' => $exit_distance,
                'created_at' => now(),
                'updated_at' => now()
            ]);
    
            $date->addDay(); // Tambahkan 1 hari ke depan
        }
    }
}
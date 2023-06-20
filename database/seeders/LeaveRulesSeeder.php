<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('leave_rules')->insert([
            'leave_name' => 'Cuti Tahunan',
        ]);
        DB::table('leave_rules')->insert([
            'leave_name' => 'Cuti Sakit',
        ]);
        DB::table('leave_rules')->insert([
            'leave_name' => 'Cuti Melahirkan',
        ]);
        DB::table('leave_rules')->insert([
            'leave_name' => 'Cuti Alasan Penting',
        ]);
        DB::table('leave_rules')->insert([
            'leave_name' => 'Cuti Besar',
        ]);
    }
}

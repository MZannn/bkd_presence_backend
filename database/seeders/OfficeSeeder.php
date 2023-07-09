<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offices')->insert([
            'name' => 'Badan Kepegawaian Daerah Provinsi Kalimantan Tengah',
            'address'=>'Jl. Willem AS No.11',
            'latitude'=>-2.2177723208609805,
            'longitude'=>113.91701102256775,
            'radius'=>0.05,
            'start_work'=>'07:00:00',
            'start_break'=>'08:00:00',
            'late_tolerance'=>'09:00:00',
            'end_work'=>'15:30:00',
        ]);
    }
}

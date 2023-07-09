<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees')->insert([
            'nip' => '2001081320230408079',
            'name' => 'Muhammad Fauzan',
            'password'=>'2001081320230408079',
            'position'=>"Anggota Sub Bagian Pengelolaan Data",
            'phone_number'=>"081234567890",
            'device_id'=>null,
            'office_id'=>1,
        ]);
    }
}

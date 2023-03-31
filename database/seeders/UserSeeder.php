<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'email' => 'mfauzan1098@gmail.com',
            'password' => Hash::make('123123123'),
            'roles' => 'SUPER ADMIN',
            'office_id'=> null

        ]);
    }
}
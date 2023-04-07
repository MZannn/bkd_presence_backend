<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class EmployeeImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Employee([
            'nip'=> strval($row['nip']),
            'name'=> strval($row['nama']),
            'position'=> strval($row['jabatan']),
            'phone_number'=> strval($row['no_hp']),
            'password'=> Hash::make($row['password']),
            'office_id'=> Auth::user()->office_id ?? $row['id_kantor'],
        ]);
    }
}

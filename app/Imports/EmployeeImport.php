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
            'nip'=> $row['nip'],
            'name'=> $row['name'],
            'division'=> $row['division'],
            'phone_number'=> $row['phone_number'],
            'office_id'=> Auth::user()->office_id ?? $row['office_id'],
            'password'=> Hash::make($row['password']),
        ]);
    }
}

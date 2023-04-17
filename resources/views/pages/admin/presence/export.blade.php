<table>
    <thead>
        <tr>
            <th>NIP</th>
            <th>Nama</th>
            <th>Kantor</th>
            <th>Hari Kerja</th>
            <th>Hadir</th>
            <th>Izin</th>
            <th>Sakit</th>
            <th>Tidak Hadir</th>
            <th>Terlambat Dalam Menit</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
            <tr>
                <td> {{ $item->employee->nip }} </td>
                <td> {{ $item->employee->name }} </td>
                <td> {{ $item->office->name }}</td>
                <td> {{ $total_working_days }}</td>
                <td>
                    {{ $item->where('attendance_entry_status', 'HADIR')->where('attendance_exit_status', 'HADIR')->count() }}
                </td>
                <td>
                    {{ $item->where('attendance_entry_status', 'IZIN')->count() }}
                </td>
                <td>
                    {{ $item->where('attendance_entry_status', 'SAKIT')->count() }}
                </td>
                <td>
                    {{ $item->where('attendance_entry_status', null)->count() }}
                </td>
                <td>
                    -
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

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
        @php
            $total_hadir = 0;
        @endphp
        @foreach ($attendance_counts as $date => $count)
            @php
                $attendance = $count > 0 ? 'HADIR' : 'TIDAK HADIR';
                $total_hadir += $attendance === 'HADIR' ? 1 : 0;
            @endphp
        @endforeach
        @foreach ($items as $item)
            <tr>
                <td> {{ $item->employee->nip }} </td>
                <td> {{ $item->employee->name }} </td>
                <td> {{ $item->office->name }}</td>
                <td> {{ $working_days }}</td>
                <td>
                    {{ $attendance }}
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

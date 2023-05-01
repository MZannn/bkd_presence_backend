@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Detail Presensi</h1>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="card-body">
                <table class="table table-bordered">
                    @foreach ($items as $item)
                        <tr>
                            <th width="20%">ID</th>
                            <td>
                                {{ $item->id }}
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">NIP</th>
                            <td>
                                {{ $item->employee->nip }}
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">Kantor</th>
                            <td>
                                {{ $item->office->name }}
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">Tanggal Presensi</th>
                            <td>
                                {{ $item->presence_date }}
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">Jam Presensi Masuk</th>
                            <td>
                                {{ $item->attendance_clock }}
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">Jam Presensi Keluar </th>
                            <td>
                                {{ $item->attendance_clock_out }}
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">Status Presensi Masuk</th>
                            <td>
                                {{ $item->attendance_entry_status }}
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">Status Presensi Keluar</th>
                            <td>
                                {{ $item->attendance_exit_status }}
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">Lokasi Presensi Masuk</th>
                            <td>
                                {{ $item->entry_position }}
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">Jarak Presensi Masuk</th>
                            <td>
                                {{ $item->entry_distance }} m
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">Lokasi Presensi Keluar</th>
                            <td>
                                {{ $item->exit_position }}
                            </td>
                        </tr>
                        <tr>
                            <th width="20%">Jarak Presensi Keluar</th>
                            <td>
                                {{ $item->exit_distance }} m
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>
@endsection

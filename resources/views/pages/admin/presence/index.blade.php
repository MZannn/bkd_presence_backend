@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Presensi Pegawai
                @if (Auth::user()->roles == 'SUPER ADMIN')
                    {{ $items->first()->office->name ?? 'Tidak Ditemukan' }}
                @else
                    {{ Auth::user()->office->name ?? 'Tidak Ditemukan' }}
                @endif
            </h1>
        </div>

        <div class="row d-sm-flex justify-content-sm-between">
            <div class="col-sm-4">
                <label for="">Cari Data Presensi</label>
                <form action="{{ route('presence.index') }}" method="GET" class="row">
                    @csrf
                    <div class="form-group col-sm-5">
                        <label for="start_date" class="col-form-label">Dari
                            Tanggal -
                        </label>
                        <input type="date" class="form-control" placeholder="" name="start_date">
                    </div>
                    <div class="form-group col-sm-5">
                        <label for="end_date" class="col-form-label">Hingga Tanggal
                        </label>
                        <input type="date" class="form-control" placeholder="" name="end_date">
                    </div>
                    <div class="form-group col-sm-10">
                        <label for="search" class="col-form-label">Cari
                            Pegawai</label>
                        <input type="number" class="form-control" placeholder="Masukkan Nip Pegawai" name="search"
                            aria-label="Recipient's username" aria-describedby="basic-addon2" style="">

                    </div>
                    <button type="submit" class="btn btn-primary ml-3 col-sm-3"><i class="fa fa-search"></i></button>
                </form>
            </div>
            <div class="col-sm-8">
                @if (Auth::user()->roles == 'SUPER ADMIN')
                    <form action="{{ route('presence.index') }}" method="GET"
                        class="d-sm-flex justify-content-sm-end mb-2">
                        <label for="office_id"
                            class="col-sm-1 col-form-label d-sm-flex justify-content-sm-end">Kantor</label>
                        <div class="input-group col-sm-5">
                            <select class="form-select form-control" name="office_id" id=""
                                aria-label="Default select example">
                                <option value="">Pilih Kantor</option>
                                @foreach ($offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary input-group-text">Pilih</button>
                        </div>
                    </form>
                @endif
                <div class="row d-sm-flex justify-content-sm-end">
                    <a href="{{ route('presence.create') }}"
                        class="btn btn-success shadow-sm d-sm-flex justify-content-sm-end col-sm-4 mr-4"><i
                            class="fas fa-file-import fa-md text-white mx-2 my-2"> Export Data Rekapan Presensi</i></a>
                </div>
            </div>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Kantor</th>
                                <th>Tanggal Presensi</th>
                                <th>Presensi Masuk</th>
                                <th>Presensi Keluar</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td> {{ $item->employee->nip }} </td>
                                    <td> {{ $item->employee->name }} </td>
                                    <td> {{ $item->office->name }}</td>
                                    <td> {{ \Carbon\Carbon::parse($item->presence_date)->format('d-m-Y') }} </td>
                                    <td> {{ $item->attendance_entry_status }} </td>
                                    <td> {{ $item->attendance_exit_status }} </td>
                                    <td>
                                        <a href="{{ route('presence.show', $item->id) }}" class="btn btn-info">
                                            <i class="fa fa-eye"></i></a>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        Data Tidak Ada
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-sm-flex justify-content-sm-end mb-2">
                        {{ $items->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

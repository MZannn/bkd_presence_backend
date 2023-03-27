@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Presensi Pegawai
            </h1>
        </div>
        <div class="row d-sm-flex justify-content-sm-between">
            {{-- <div class="col-sm-4">
                <a href="{{ route('employee.import') }}" class="btn btn-sm btn-success shadow-sm"><i
                        class="fas fa-file-import fa-md text-white mx-2 my-2"> Import Data Pegawai</i></a>
            </div> --}}
            <div class="col-sm-8">
                {{-- 
                <form action="{{ route('employee.index') }}" method="GET" class="d-sm-flex justify-content-sm-end">
                    <label for="search" class="col-sm-3 col-form-label d-sm-flex justify-content-sm-end">Cari
                        Pegawai</label>
                    <div class="input-group col-sm-5">
                        <input type="number" class="form-control" placeholder="Masukkan Nip Pegawai" name="search"
                            aria-label="Recipient's username" aria-describedby="basic-addon2" style="">
                        <button type="submit" class="input-group-text btn btn-primary"><i
                                class="fa fa-search"></i></button>
                    </div>
                </form> --}}
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
                                <th>Tanggal Presensi</th>
                                <th>Status Presensi</th>
                                <th>Action</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td> {{ $item->employee->nip }} </td>
                                    <td> {{ $item->employee->name }} </td>
                                    <td> {{ $item->presence_date }} </td>
                                    <td> {{ $item->presence_status }} </td>
                                    <td>
                                        <a href="{{ route('employee.edit', $item->nip) }}" class="btn btn-info">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('employee.destroy', $item->nip) }}" method="POST"
                                            class="d-sm-inline" id="delete">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

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

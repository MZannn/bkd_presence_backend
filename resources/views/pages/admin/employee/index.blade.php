@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pegawai @if (Auth::user()->roles == 'SUPER ADMIN')
                    {{ $items->first()->office->name ?? 'Tidak Ditemukan' }}
                @else
                    {{ Auth::user()->office->name ?? 'Tidak Ditemukan' }}
                @endif
            </h1>
            <a href="{{ route('employee.create') }}" class="btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-plus fa-sm text-white">
                    Tambah Pegawai</i></a>
        </div>
        <div class="row d-sm-flex justify-content-sm-between">
            <div class="col-sm-4">
                <a href="{{ route('employee.import') }}" class="btn btn-sm btn-success shadow-sm mb-2"><i
                        class="fas fa-file-import fa-md text-white mx-2 my-2"> Import Data Pegawai</i>
                </a>
                @if (Auth::user()->roles == 'ADMIN')
                    <a href="{{ url(Storage::url($template->file)) }}" class="btn btn-success">
                        Template Import Data Pegawai
                    </a>
                @elseif (Auth::user()->roles == 'SUPER ADMIN')
                    @if ($template->count() == 0)
                        <a href="{{ route('employee.insertTemplate') }}" class="btn btn-success">
                            Tambahkan Template Import Data Pegawai
                        </a>
                    @else
                        <a href="{{ route('employee.changeTemplate', $template->id) }}" class="btn btn-success">
                            Ganti Template Import Data Pegawai
                        </a>
                    @endif
                @endif
            </div>

            <div class="col-sm-8">
                @if (Auth::user()->roles == 'SUPER ADMIN')
                    <form action="{{ route('employee.index') }}" method="GET"
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
                <form action="{{ route('employee.index') }}" method="GET" class="d-sm-flex justify-content-sm-end">
                    <label for="search" class="col-sm-3 col-form-label d-sm-flex justify-content-sm-end">Cari
                        Pegawai</label>
                    <div class="input-group col-sm-5">
                        <input type="number" class="form-control" placeholder="Masukkan Nip Pegawai" name="search"
                            aria-label="Recipient's username" aria-describedby="basic-addon2" style="">
                        <button type="submit" class="input-group-text btn btn-primary"><i
                                class="fa fa-search"></i></button>
                    </div>
                </form>
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
                                <th>Jabatan</th>
                                <th>No Hp</th>
                                <th>Kantor</th>
                                <th>Foto</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td> {{ $item->nip }} </td>
                                    <td> {{ $item->name }} </td>
                                    <td width="15%"> {{ $item->position }} </td>
                                    <td> {{ $item->phone_number }} </td>
                                    <td width="15%"> {{ $item->office->name }}</td>

                                    <td width="10%"> <img src="{{ Storage::url($item->profile_photo_path) }}"
                                            alt="" style="width: 150px" class="img-thumbnail"></td>
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

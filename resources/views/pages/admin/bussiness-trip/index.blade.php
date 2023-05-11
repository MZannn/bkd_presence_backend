@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Perjalanan Dinas @if (Auth::user()->roles == 'SUPER ADMIN')
                    {{ $items->first()->office->name ?? 'Tidak Ditemukan' }}
                @else
                    {{ Auth::user()->office->name ?? 'Tidak Ditemukan' }}
                @endif
            </h1>
        </div>
        <div class="row d-sm-flex justify-content-sm-between">
            <div class="col-sm-4">
            </div>

            <div class="col-sm-8">
                @if (Auth::user()->roles == 'SUPER ADMIN')
                    <form action="{{ route('bussinessTrip') }}" method="GET" class="d-sm-flex justify-content-sm-end mb-2">
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
                <form action="{{ route('bussinessTrip') }}" method="GET" class="d-sm-flex justify-content-sm-end">
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
                                <th>Kantor</th>
                                <th>Tanggal Mulai - Tanggal Berakhir</th>
                                <th>Jam Mulai - Jam Berakhir</th>
                                <th>Surat Dinas</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <form action="{{ route('bussinessTrip.validation') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <tr>
                                        <input type="hidden" name="employee_id" value="{{ $item->employee_id }}">
                                        <input type="hidden" name="office_id" value="{{ $item->office_id }}">
                                        <input type="hidden" name="presence_id" value="{{ $item->presence_id }}">
                                        <input type="hidden" name="start_time" value="{{ $item->start_time }}">
                                        <input type="hidden" name="end_time" value="{{ $item->end_time }}">
                                        <input type="hidden" name="start_date" value="{{ $item->start_date }}">
                                        <input type="hidden" name="end_date" value="{{ $item->end_date }}">
                                        <td> {{ $item->employee->nip }} </td>
                                        <td> {{ $item->employee->name }} </td>
                                        <td> {{ $item->office->name }} </td>
                                        <td width="15%"> {{ $item->start_date }} - {{ $item->end_date }}
                                        </td>
                                        <td> {{ \Carbon\Carbon::parse($item->start_date)->format('d-m-Y') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('d-m-Y') }} </td>
                                        <td>
                                            @if (pathinfo($item->file, PATHINFO_EXTENSION) == 'pdf')
                                                <a href="{{ url(Storage::url($item->file)) }}" class="btn btn-primary">
                                                    PDF
                                                </a>
                                            @else
                                                <a href="{{ url(Storage::url($item->file)) }}"><img
                                                        src="{{ Storage::url($item->file) }}" alt=""
                                                        style="width: 150px" class="img-thumbnail"></a>
                                            @endif

                                        </td>
                                        <td width="12%">
                                            <select class="form-select form-control" name="status" id=""
                                                aria-label="Default select example">
                                                <option value="{{ $item->status }}" selected>
                                                    {{ $item->status }}
                                                </option>
                                                <option value="KONFIRMASI">KONFIRMASI</option>
                                                <option value="TOLAK">TOLAK</option>
                                            </select>
                                        </td>
                                        <td width="13%">
                                            <a href="{{ route('bussinessTrip.edit', $item->id) }}" class="btn btn-info">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>
                                            <button type="submit" class="btn btn-primary d-sm-inline">
                                                Validasi
                                            </button>
                                        </td>
                                    </tr>
                                </form>
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

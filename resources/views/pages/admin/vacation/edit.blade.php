@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-item-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Permintaan Cuti</h1>
        </div>


        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Content Row -->
        <div class="row">
            <div class="card-body">

                <form action="{{ route('vacation.validation') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" class="form-control" name='nip' value="{{ $item->nip }}" readonly>
                        {{-- <input type="hidden" name="nip" value="{{ $item->nip }}"> --}}
                    </div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" name='name' value="{{ $item->employee->name }}"
                            readonly>
                    </div>
                    <div class="form-group">
                        <label for="office_name">Kantor</label>
                        <input type="text" class="form-control" name='office_name' value="{{ $item->office->name }}"
                            readonly>
                        <input type="hidden" name="office_id" value="{{ $item->office_id }}">
                    </div>
                    <div class="form-group">
                        <label for="leave_type">Jenis Cuti</label>
                        <input type="text" class="form-control" name='leave_type' value="{{ $item->leave_type }}"
                            readonly>
                    </div>
                    <div class="form-group">
                        <label for="reason">Alasan Cuti</label>
                        <input type="text" class="form-control" name='reason' value="{{ $item->reason }}"
                            readonly>
                    </div>

                    <div class="form-group row" style="margin-left: -12px">
                        <div class="col-auto">
                            <label for="start_date" class="d-sm-flex justify-content-end">Dimulai dari tanggal </label>
                            <input type="date" class="form-control" name='start_date' value="{{ $item->start_date }}">
                        </div>
                        <div class="col-auto" style="margin-left: -15px; margin-right:-15px">
                            <label for="">-</label>
                        </div>
                        <div class="col-auto">
                            <label for="end_date">Hingga Tanggal </label>
                            <input type="date" class="form-control" name='end_date' value="{{ $item->end_date }}">
                        </div>
                    </div>
                    <div class="form-group" style="margin-left: -12px">
                        <div class="col-auto">
                            @if (pathinfo($item->file, PATHINFO_EXTENSION) == 'pdf')
                                <a href="{{ url(Storage::url($item->file)) }}" class="btn btn-danger">Download
                                    PDF</a>
                            @else
                                <a href="{{ url(Storage::url($item->file)) }}"><img src="{{ Storage::url($item->file) }}"
                                        alt="" style="width: 150px" class="img-thumbnail"></a>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-select form-control" name="status" id=""
                            aria-label="Default select example">
                            <option value="{{ $item->status }}" selected>{{ $item->status }}
                            </option>
                            <option value="KONFIRMASI">KONFIRMASI</option>
                            <option value="TOLAK">TOLAK</option>

                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary px-5">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection

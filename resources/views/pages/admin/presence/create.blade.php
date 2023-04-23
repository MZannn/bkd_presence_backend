@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Export Rekapan Presensi</h1>
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
                <form action="{{ route('presence.export') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-3">
                            <label for="start_date" class="col-form-label">Dari
                                Tanggal -
                            </label>
                            <input type="date" class="form-control" placeholder="" name="start_date">
                        </div>
                        <div class="form-group col-sm-3">
                            <label for="end_date" class="col-form-label">Hingga Tanggal
                            </label>
                            <input type="date" class="form-control" placeholder="" name="end_date">
                        </div>
                        @if (Auth::user()->roles == 'SUPER ADMIN')
                            <div class="form-group col-sm-3">
                                <label for="end_date" class="col-form-label">Kantor
                                </label>
                                <select class="form-select form-control" name="office_id" id=""
                                    aria-label="Default select example">
                                    <option value="">Pilih Kantor</option>
                                    @foreach ($offices as $office)
                                        <option value="{{ $office->id }}">{{ $office->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @elseif (Auth::user()->roles == 'ADMIN')
                            <input type="hidden" name="office_id" value="{{ Auth::user()->office_id }}">
                        @endif
                    </div>
                    <button type="submit" class="btn btn-success px-5"><i
                            class="fas fa-file-import fa-md text-white mx-2 my-2"> Export Data Rekapan Presensi</i></button>
                </form>
            </div>
        </div>
    </div>
@endsection

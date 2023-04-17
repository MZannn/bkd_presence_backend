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
                    <p>Tambahan Libur (Opsional/Boleh Tidak Diisi)</p>
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
                    <button type="submit" class="btn btn-primary px-5">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Scrape Tanggal Libur</h1>
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
                <form action="{{ route('holiday.storeScrape') }}" method="post">
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
                    </div>
                    <button type="submit" class="btn btn-primary px-5">Ambil Data</button>
                </form>
            </div>
        </div>
    </div>
@endsection

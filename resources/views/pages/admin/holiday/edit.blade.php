@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Ubah Data Hari Libur</h1>
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

                <form action="{{ route('holiday.update', $item->id) }}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf

                    <label for="holiday_date" class="d-sm-flex">Tanggal Hari Libur </label>
                    <input type="date" class="form-control mb-2" name='holiday_date' value="{{ $item->holiday_date }}">
                    <button type="submit" class="btn btn-primary px-5">Submit</button>

                </form>
            </div>
        </div>
    </div>
@endsection

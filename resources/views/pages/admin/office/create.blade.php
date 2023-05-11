@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tambah Kantor</h1>
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
                <form action="{{ route('office.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama Kantor</label>
                        <input type="text" class="form-control" name='name' value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat Kantor</label>
                        <input type="text" class="form-control" name='address' id="address" value="{{ old('address') }}">
                    </div>
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" class="form-control" name='latitude' id="latitude" value="{{ old('latitude') }}">
                    </div>
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control" name='longitude' id="longitude" value="{{ old('longitude') }}">
                    </div>
                    <div class="form-group">
                        <label for="radius">Radius Presensi</label>
                        <input type="number" class="form-control" name='radius' id="radius" value="{{ old('radius') }}" placeholder="Radius Dalam satuan meter">
                    </div>
                    <div id="map" style="height: 400px; width: 100%;" class="form-group">
                        <iframe width="600" height="450" frameborder="0" style="border:0"
                            src="https://www.google.com/maps/embed/v1/place?q={{ $location }}&zoom=15&key=AIzaSyA_KUAyGozVXUuA1h-QzMHxCS8OdKMzEpE"
                            allowfullscreen></iframe>
                    </div>
                    <div class="form-group row" style="margin-left: -12px">
                        <div class="col-auto">
                            <label for="start_work">Jam Mulai Kerja</label>
                            <input type="time" class="form-control" name='start_work' value="{{ old('start_work') }}">
                        </div>
                        <div class="col-auto">
                            <label for="start_break">Batas Jam Kehadiran</label>
                            <input type="time" class="form-control" name='start_break' value="{{ old('start_break') }}">
                        </div>
                        <div class="col-auto">
                            <label for="late_tolerance">Batas Toleransi Keterlambatan</label>
                            <input type="time" class="form-control" name='late_tolerance' value="{{ old('late_tolerance') }}">
                        </div>
                        
                        <div class="col-auto">
                            <label for="end_work">Jam Selesai Kerja</label>
                            <input type="time" class="form-control" name='end_work' value="{{ old('end_work') }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary px-5">Submit</button>

                </form>
            </div>
        </div>
    </div>
@endsection

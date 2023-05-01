@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Kelola Kantor {{ $item->name }}</h1>
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
                <form action="{{ route('office.update', $item->id) }}" method="post">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama Kantor</label>
                        <input type="text" class="form-control" name='name' value="{{ $item->name }}">
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat Kantor</label>
                        <input type="text" class="form-control" name='address' value="{{ $item->address }}">
                    </div>
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" class="form-control" name='latitude' value="{{ $item->latitude }}"
                            id="latitude">
                    </div>
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control" name='longitude' value="{{ $item->longitude }}"
                            id="longitude">
                    </div>
                    <div class="form-group">
                        <label for="radius">Radius Presensi</label>
                        <input type="number" class="form-control" name='radius' id="radius"
                            value="{{ $item->radius * 1000 }}" placeholder="Radius Dalam satuan meter">
                    </div>
                    <div id="map" style="height: 400px; width: 100%;" class="form-group">
                        <embed
                            src="https://www.google.com/maps/embed/v1/place?q={{ $item->latitude }},{{ $item->longitude }}&zoom=15&center={{ $item->latitude }},{{ $item->longitude }}&key=AIzaSyA_KUAyGozVXUuA1h-QzMHxCS8OdKMzEpE"
                            type="">
                        <iframe width="600" height="450" frameborder="0" style="border:0"
                            src="https://www.google.com/maps/embed/v1/place?q={{ $item->latitude }},{{ $item->longitude }}&zoom=15&center={{ $item->latitude }},{{ $item->longitude }}&key=AIzaSyA_KUAyGozVXUuA1h-QzMHxCS8OdKMzEpE"
                            allowfullscreen></iframe>
                    </div>
                    <button type="submit" class="btn btn-primary px-5">Ubah</button>

                </form>
            </div>
        </div>
    </div>
@endsection

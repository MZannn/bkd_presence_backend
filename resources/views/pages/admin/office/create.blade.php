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
                        <input type="text" class="form-control" name='address' value="{{ old('address') }}">
                    </div>
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" class="form-control" name='latitude' value="{{ old('latitude') }}">
                    </div>
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" class="form-control" name='longitude' value="{{ old('longitude') }}">
                    </div>

                    <button type="submit" class="btn btn-primary px-5">Submit</button>

                </form>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Ubah Password Admin</h1>
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
                <form action="{{ route('changePassword.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="old_password">Password Lama</label>
                        <input type="password" class="form-control" name='old_password' value="">
                    </div>
                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" class="form-control" name='password' value="">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" name='password_confirmation' value="">
                    </div>
                    <button type="submit" class="btn btn-primary px-5">Ubah Password Admin</button>

                </form>
            </div>
        </div>
    </div>
@endsection

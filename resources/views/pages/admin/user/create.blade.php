@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tambah Admin</h1>
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
                <form action="{{ route('user.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name='email' value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name='password' value="{{ old('password') }}">
                    </div>
                    <div class="form-group">
                        <label for="roles">Roles</label>
                        <select class="form-select form-control" name="roles" id=""
                            aria-label="Default select example">
                            <option value="">Pilih Role</option>
                            <option value="ADMIN">Admin</option>
                            <option value="OFFICE HEAD">Kepala Kantor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="office_id">Kantor</label>
                        <select class="form-select form-control" name="office_id" id=""
                            aria-label="Default select example">
                            <option value="">Pilih Kantor</option>
                            @foreach ($offices as $office)
                                <option value="{{ $office->id }}">{{ $office->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary px-5">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tambah Pegawai</h1>
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
                <form action="{{ route('employee.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" class="form-control" name='nip' value="{{ old('nip') }}">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name='password' value="{{ old('password') }}">
                    </div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" name='name' value="{{ old('name') }}">
                    </div>
                    <div class="form-group">
                        <label for="position">Jabatan</label>
                        <input type="text" class="form-control" name='position' value="{{ old('position') }}">
                    </div>
                    <div class="form-group">
                        <label for="phone_number">No Hp</label>
                        <input type="number" class="form-control" name='phone_number' value="{{ old('phone_number') }}">
                    </div>
                    <div class="form-group">
                        <label for="profile_photo_path">Foto</label>
                        <input type="file" class="form-control" name='profile_photo_path' placeholder="Foto Profil"
                            value="{{ old('profile_photo_path') }}">
                    </div>
                    @if (Auth::user()->roles == 'SUPER ADMIN')
                        <div class="form-group">
                            <label for="office_id">Kantor</label>
                            <select class="form-select form-control" name="office_id" id=""
                                aria-label="Default select example">
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif (Auth::user()->roles == 'ADMIN')
                        <input type="hidden" name="office_id" value="{{ Auth::user()->office_id }}">
                    @endif
                    <button type="submit" class="btn btn-primary px-5">Submit</button>

                </form>
            </div>
        </div>
    </div>
@endsection

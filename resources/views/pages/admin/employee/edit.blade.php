@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Ubah Data Pegawai</h1>
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

                <form action="{{ route('employee.update', $employee->nip) }}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf

                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" class="form-control" name='nip' value="{{ $employee->nip }}" readonly>
                    </div>
                    {{-- <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name='password' value="{{ $employee->password }}">
                        </div> --}}
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" name='name' value="{{ $employee->name }}">
                    </div>
                    <div class="form-group">
                        <label for="position">Jabatan</label>
                        <input type="text" class="form-control" name='position' value="{{ $employee->position }}">
                    </div>
                    <div class="form-group">
                        <label for="phone_number">No Hp</label>
                        <input type="number" class="form-control" name='phone_number'
                            value="{{ $employee->phone_number }}">
                    </div>
                    <div class="form-group">
                        <label for="profile_photo_path">Foto</label>
                        <div class="">
                            <img src="{{ Storage::url($employee->profile_photo_path) }}" alt=""
                                style="max-height: 150px; margin-bottom:10px;" class="img-thumbnail">
                        </div>
                        <input type="file" class="form-control" name='profile_photo_path' placeholder="Foto Profil"
                            value="{{ $employee->profile_photo_path }}">
                    </div>


                    @if (Auth::user()->roles == 'SUPER ADMIN')
                        <div class="form-group">
                            <label for="office_id">Kantor</label>
                            <select class="form-select form-control" name="office_id" id=""
                                aria-label="Default select example">
                                <option value="{{ $employee->office_id }}" selected>{{ $employee->office->name }}
                                </option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="office_id" value="{{ Auth::user()->office_id }}">
                    @endif
                    <div class="form-group">
                        <label for="device_id">ID Device</label>
                        <input type="text" class="form-control" name='device_id' value="{{ $employee->device_id }}" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary px-5">Submit</button>

                </form>
            </div>
        </div>
    </div>
@endsection

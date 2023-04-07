@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Ubah Data Admin {{$item->office->name }}</h1>
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
                <form action="{{ route('user.update', $item->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name='email' value="{{ $item->email }}">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name='password' value="{{ $item->password }}">
                    </div>

                    <button type="submit" class="btn btn-primary px-5">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection

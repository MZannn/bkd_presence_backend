@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Validasi Perjalanan Dinas</h1>
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

                <form action="{{ route('bussinessTrip.validation') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="employee_id">Nama</label>
                        <input type="text" class="form-control" name='employee_id' value="{{ $items->employee_id }}"
                            readonly>
                        {{-- <input type="hidden" name="employee_id" value="{{ $items->employee_id }}"> --}}
                    </div>
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" name='name' value="{{ $items->employee->name }}"
                            readonly>
                    </div>
                    <div class="form-group">
                        <label for="office_name">Kantor</label>
                        <input type="text" class="form-control" name='office_name' value="{{ $items->office->name }}"
                            readonly>
                            <input type="hidden" name="office_id" value="{{$items->office_id}}">
                    </div>
                    <div class="form-group row" style="margin-left: -12px">
                        <div class="col-auto">
                            <label for="start_date" class="d-sm-flex justify-content-end">Dimulai dari tanggal </label>
                            <input type="date" class="form-control" name='start_date' value="{{ $items->start_date }}">
                        </div>
                        <div class="col-auto" style="margin-left: -15px; margin-right:-15px">
                            <label for="">-</label>
                        </div>
                        <div class="col-auto">
                            <label for="end_date">Hingga Tanggal </label>
                            <input type="date" class="form-control" name='end_date' value="{{ $items->end_date }}">
                        </div>
                    </div>
                    <div class="form-group row" style="margin-left: -12px">
                        <div class="col-auto">
                            <label for="start_time" class="d-sm-flex justify-content-end">Dimulai dari jam </label>
                            <input type="time" class="form-control" name='start_time' value="{{ $items->start_time }}">
                        </div>
                        <div class="col-auto" style="margin-left: -15px; margin-right:-15px">
                            <label for="">-</label>
                        </div>
                        <div class="col-auto">
                            <label for="end_time">Hingga jam </label>
                            <input type="time" class="form-control" name='end_time' value="{{ $items->end_time }}">
                        </div>
                    </div>
                    <div class="form-group" style="margin-left: -12px">
                        <div class="col-auto">
                            <a href="{{ url(Storage::url($items->file)) }}" download="false"
                                class="btn btn-danger">Download
                                PDF</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-select form-control" name="status" id=""
                            aria-label="Default select example">
                            <option value="{{ $items->status }}" selected>{{ $items->status }}
                            </option>
                            <option value="HADIR">HADIR</option>

                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary px-5">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection

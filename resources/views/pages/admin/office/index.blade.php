@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Kantor</h1>
            <a href="{{ route('office.create') }}" class="btn btn-sm btn-primary align-items-end shadow-sm"><i
                    class="fas fa-plus fa-sm text-white">
                    Tambah Kantor</i></a>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kantor</th>
                                <th>Alamat</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Radius Presensi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($items as $item)
                                <tr>
                                    <td> {{ $item->id }} </td>
                                    <td width="20%"> {{ $item->name }} </td>
                                    <td width="40%"> {{ $item->address }} </td>
                                    <td> {{ $item->latitude }} </td>
                                    <td> {{ $item->longitude }} </td>
                                    <td> {{ $item->radius * 1000 }} m</td>
                                    <td width="15%">
                                        <a href="{{ route('office.edit', $item->id) }}" class="btn btn-info">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('office.destroy', $item->id) }}" method="POST"
                                            class="d-sm-inline">
                                            @csrf
                                            @method('delete')
                                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{$item->id}})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        Data Tidak Ada
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

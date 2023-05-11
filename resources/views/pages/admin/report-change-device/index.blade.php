@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Permintaan Penggantian Devices </h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NIP</th>
                                <th>Nama</th>
                                <th>Kantor</th>
                                <th>Status</th>
                                <th>Alasan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <form action="{{ route('reportChangeDevice.approved') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <tr>
                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <input type="hidden" name="nip" value="{{ $item->nip }}">
                                        <td> {{ $item->employee->nip }} </td>
                                        <td> {{ $item->employee->name }} </td>
                                        <td> {{ $item->office->name }} </td>
                                        <td width="12%">
                                            <select class="form-select form-control" name="status" id=""
                                                aria-label="Default select example">
                                                <option value="{{ $item->status }}" selected>
                                                    {{ $item->status }}
                                                </option>
                                                <option value="APPROVED">SETUJUI</option>
                                            </select>
                                        </td>
                                        <td>{{ $item->reason }}</td>
                                        <td width="13%">
                                            <button type="submit" class="btn btn-primary d-sm-inline">
                                                Konfirmasi
                                            </button>
                                        </td>
                                    </tr>
                                </form>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        Data Tidak Ada
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-sm-flex justify-content-sm-end mb-2">
                        {{ $items->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

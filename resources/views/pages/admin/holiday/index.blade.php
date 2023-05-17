@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Tanggal Libur Nasional </h1>
            <a href="{{ route('holiday.create') }}" class="btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-plus fa-sm text-white">
                    Tambah Hari Libur</i></a>
        </div>
        <div class="row d-sm-flex justify-content-sm-between">
            <div class="col-sm-4">
                <a href="{{ route('holiday.scrape') }}" class="btn btn-sm btn-success shadow-sm mb-2"><i
                        class="fas fa-file-import fa-md text-white mx-2 my-2">Scrape Hari Libur</i>
                </a>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td> {{ \Carbon\Carbon::parse($item->holiday_date)->format('d-m-Y') }} </td>
                                    <td>
                                        <a href="{{ route('holiday.edit', $item->id) }}" class="btn btn-info">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('holiday.destroy', $item->id) }}" method="POST"
                                            class="d-sm-inline" id="form-delete-{{ $item->id }}">
                                            @csrf
                                            @method('delete')
                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmDelete({{ $item->id }})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
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

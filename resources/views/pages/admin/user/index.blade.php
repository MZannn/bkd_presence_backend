@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Admin</h1>
            <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary align-items-end shadow-sm"><i
                    class="fas fa-plus fa-sm text-white">
                    Tambah Admin</i></a>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Kantor</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse ($items as $item)
                                <tr>
                                    <td> {{ $item->id }} </td>
                                    <td width="20%"> {{ $item->email }} </td>
                                    <td width="40%"> {{ $item->roles }} </td>
                                    <td> {{ $item->office->name }} </td>
                                    <td>
                                        <a href="{{ route('office.edit', $item->id) }}" class="btn btn-info">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('office.destroy', $item->id) }}" method="POST"
                                            class="d-sm-inline">
                                            @csrf
                                            @method('delete')
                                            <button class="btn btn-danger">
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

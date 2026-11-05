@extends('layouts.app')

@section('title', 'Manage Administrators')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Administrators</h2>
            <p class="text-muted">Manage CodeArena administrator accounts.</p>
        </div>

        <a href="{{ route('admin.admin-users.create') }}"
           class="btn btn-primary">
            Add Administrator
        </a>
    </div>



    <div class="card shadow-sm">
        <div class="card-body">

            @if ($admins->isEmpty())
                <p class="text-muted mb-0">No administrators found.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Created</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($admins as $admin)
                                <tr>
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ $admin->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection

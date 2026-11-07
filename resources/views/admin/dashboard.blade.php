@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h2>Administrator Dashboard</h2>
        <p class="text-muted">
            Manage your institution's CodeArena platform.
        </p>
    </div>

    <div class="row g-4">

    {{-- Institution Settings --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Institution Settings</h5>
                    <p class="card-text text-muted">
                        Manage institution information and contact details.
                    </p>
                    @cannot('manage-institution-settings')
                        <a href="{{ route('admin.settings.edit') }}"
                       class="btn btn-primary">
                        Show Settings
                    </a>
                    @endcannot
                    @can('manage-institution-settings')
                        <a href="{{ route('admin.settings.edit') }}"
                            class="btn btn-primary">
                                Manage Settings
                        </a>
                    @endcan


                </div>
            </div>
        </div>



        {{-- Contests --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Contests</h5>
                    <p class="card-text text-muted">
                        Create and manage programming contests.
                    </p>
                    <span class="badge bg-secondary">Coming Soon</span>
                </div>
            </div>
        </div>

        {{-- Problems --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Problems</h5>
                    <p class="card-text text-muted">
                        Manage programming problems and test cases.
                    </p>
                    <span class="badge bg-secondary">Coming Soon</span>
                </div>
            </div>
        </div>

        {{-- Registrations --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Registrations</h5>
                    <p class="card-text text-muted">
                        Review contestant and team registrations.
                    </p>
                    <span class="badge bg-secondary">Coming Soon</span>
                </div>
            </div>
        </div>

        {{-- Teams --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Teams</h5>
                    <p class="card-text text-muted">
                        Manage approved teams and their accounts.
                    </p>
                    <span class="badge bg-secondary">Coming Soon</span>
                </div>
            </div>
        </div>

        {{-- Reports --}}
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Reports</h5>
                    <p class="card-text text-muted">
                        View contest statistics and administrative reports.
                    </p>
                    <span class="badge bg-secondary">Coming Soon</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Administrators</h5>

                    <p class="card-text text-muted">
                        Manage administrator accounts and access.
                    </p>
                    @can('manage-administrators')
                        <a href="{{ route('admin.admin-users.index') }}"
                        class="btn btn-primary">
                        Manage Administrators
                        </a>
                    @endcan
                    @cannot('manage-administrators')
                        <a href="{{ route('admin.admin-users.index') }}"
                        class="btn btn-primary">
                        Show Adminstraters
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Audit Logs</h5>

                    <p class="card-text text-muted">
                        Review the history of administrative activities.
                    </p>

                    <a href="{{ route('admin.audit-logs.index') }}"
                    class="btn btn-primary">
                        View Audit Logs
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

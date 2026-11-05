@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h2>Administrative Audit Logs</h2>
        <p class="text-muted">
            Review important actions performed by administrators.
        </p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            @if ($logs->isEmpty())
                <p class="text-muted mb-0">
                    No audit records found.
                </p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped align-middle">

                        <thead>
                            <tr>
                                <th>Administrator</th>
                                <th>Action</th>
                                <th>Target</th>
                                <th>Description</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($logs as $log)
                                <tr>
                                    <td>
                                        {{ $log->admin?->name ?? 'Unknown' }}
                                    </td>

                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $log->action }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $log->target_type ?? '-' }}
                                        {{ $log->target_id ? '#'.$log->target_id : '' }}
                                    </td>

                                    <td>
                                        {{ $log->description ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $log->created_at->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                {{ $logs->links() }}
            @endif

        </div>
    </div>

</div>
@endsection

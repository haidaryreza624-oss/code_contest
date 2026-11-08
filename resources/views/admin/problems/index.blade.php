@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Problem Management</h2>
            <p class="text-muted mb-0">
                Create and manage programming problems.
            </p>
        </div>

        <a href="{{ route('admin.problems.create') }}"
           class="btn btn-primary">
            + Create Problem
        </a>
    </div>



    @if($problems->isEmpty())
        <div class="alert alert-info">
            No problems have been created yet.
        </div>
    @else

        <div class="card shadow-sm">
            <div class="card-body table-responsive">

                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Difficulty</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($problems as $problem)
                            <tr>
                                <td>{{ $problem->id }}</td>

                                <td>
                                    <a href="{{ route('problems.show',$problem->slug) }}">{{ $problem->title }}</a>
                                </td>

                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ ucfirst($problem->difficulty) }}
                                    </span>
                                </td>

                                <td>
                                    @if($problem->status === 'published')
                                        <span class="badge bg-success">
                                            Published
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            Draft
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    {{ $problem->user->name ?? 'Unknown' }}
                                </td>

                                <td>
                                    {{ $problem->created_at->format('Y-m-d') }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.problems.test-cases.index', $problem) }}"
                                    class="btn btn-sm btn-outline-primary">
                                        Manage Tests
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">

                                        @if($problem->status === 'draft')

                                            <a href="{{ route('admin.problems.edit', $problem) }}"
                                            class="btn btn-sm btn-outline-primary">
                                                Edit
                                            </a>

                                            <form method="POST"
                                                action="{{ route('admin.problems.publish', $problem) }}"
                                                onsubmit="return confirm('Are you sure you want to publish this problem?')">

                                                @csrf

                                                <button type="submit"
                                                        class="btn btn-sm btn-success">
                                                    Publish
                                                </button>

                                            </form>

                                        @else

                                            <span class="badge bg-success">
                                                Published
                                            </span>

                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $problems->links() }}

            </div>
        </div>

    @endif

</div>
@endsection

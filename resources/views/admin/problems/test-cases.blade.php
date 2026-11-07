@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Test Case Management</h2>
            <h5 class="text-muted">{{ $problem->title }}</h5>
        </div>

        <a href="{{ route('admin.problems.index') }}"
           class="btn btn-secondary">
            Back to Problems
        </a>
    </div>





    {{-- Add Test Case --}}
    @if($problem->status === 'draft')
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <strong>Add New Test Case</strong>
            </div>

            <div class="card-body">
                <form method="POST"
                      action="{{ route('admin.problems.test-cases.store', $problem) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Input</label>
                        <textarea name="input"
                                  class="form-control"
                                  rows="4"
                                  required>{{ old('input') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Expected Output</label>
                        <textarea name="expected_output"
                                  class="form-control"
                                  rows="4"
                                  required>{{ old('expected_output') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Test Case Type</label>

                            <select name="is_sample" class="form-select" required>
                                <option value="1">Sample (Public)</option>
                                <option value="0">Hidden (Private)</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Display Order</label>

                            <input type="number"
                                   name="position"
                                   class="form-control"
                                   value="0"
                                   min="0">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Add Test Case
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- Existing Test Cases --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <strong>Existing Test Cases ({{ $testCases->count() }})</strong>
        </div>

        <div class="card-body">

            @forelse($testCases as $testCase)

                <div class="border rounded p-3 mb-3">

                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <strong>Test Case #{{ $testCase->id }}</strong>

                            @if($testCase->is_sample)
                                <span class="badge bg-success">Sample</span>
                            @else
                                <span class="badge bg-danger">Hidden</span>
                            @endif
                        </div>

                        <span class="text-muted">
                            Position: {{ $testCase->position }}
                        </span>
                    </div>

                    @if($problem->status === 'draft')

                        <form method="POST"
                              action="{{ route('admin.problems.test-cases.update', [$problem, $testCase]) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Input</label>
                                <textarea name="input"
                                          class="form-control"
                                          rows="3"
                                          required>{{ $testCase->input }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Expected Output</label>
                                <textarea name="expected_output"
                                          class="form-control"
                                          rows="3"
                                          required>{{ $testCase->expected_output }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type</label>

                                    <select name="is_sample" class="form-select">
                                        <option value="1" @selected($testCase->is_sample)>
                                            Sample (Public)
                                        </option>

                                        <option value="0" @selected(!$testCase->is_sample)>
                                            Hidden (Private)
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Position</label>

                                    <input type="number"
                                           name="position"
                                           class="form-control"
                                           value="{{ $testCase->position }}"
                                           min="0">
                                </div>
                            </div>

                            <button class="btn btn-sm btn-primary">
                                Save Changes
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ route('admin.problems.test-cases.destroy', [$problem, $testCase]) }}"
                              class="mt-2"
                              onsubmit="return confirm('Delete this test case?')">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-outline-danger">
                                Delete
                            </button>
                        </form>

                    @else

                        <p class="text-muted mb-0">
                            This problem is published. Test cases are locked.
                        </p>

                    @endif

                </div>

            @empty

                <div class="alert alert-info mb-0">
                    No test cases have been added to this problem yet.
                </div>

            @endforelse

        </div>
    </div>

</div>
@endsection

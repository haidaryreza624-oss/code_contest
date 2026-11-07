@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h2>Create Programming Problem</h2>
        <p class="text-muted">Add a new problem to your problem library.</p>
    </div>

    <form action="{{ route('admin.problems.store') }}" method="POST">
        @csrf

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please correct the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <strong>Basic Information</strong>
            </div>

            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Problem Title</label>
                    <input type="text"
                           name="title"
                           class="form-control"
                           value="{{ old('title') }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Problem Description</label>
                    <textarea name="description"
                              class="form-control"
                              rows="7"
                              required>{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Input Format</label>
                    <textarea name="input_format"
                              class="form-control"
                              rows="4"
                              required>{{ old('input_format') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Output Format</label>
                    <textarea name="output_format"
                              class="form-control"
                              rows="4"
                              required>{{ old('output_format') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Constraints</label>
                    <textarea name="constraints"
                              class="form-control"
                              rows="4"
                              required>{{ old('constraints') }}</textarea>
                </div>

            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <strong>Problem Configuration</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Difficulty</label>
                        <select name="difficulty" class="form-select" required>
                            <option value="easy" @selected(old('difficulty') === 'easy')>Easy</option>
                            <option value="medium" @selected(old('difficulty', 'medium') === 'medium')>Medium</option>
                            <option value="hard" @selected(old('difficulty') === 'hard')>Hard</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Time Limit (milliseconds)</label>
                        <input type="number"
                               name="time_limit_ms"
                               class="form-control"
                               value="{{ old('time_limit_ms', 2000) }}"
                               min="100"
                               max="60000"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Memory Limit (MB)</label>
                        <input type="number"
                               name="memory_limit_mb"
                               class="form-control"
                               value="{{ old('memory_limit_mb', 256) }}"
                               min="16"
                               max="1024"
                               required>
                    </div>

                </div>

                <div class="alert alert-info mb-0">
                    New problems are automatically saved as drafts.
                    You can add test cases before publishing them.
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.problems.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                Create Problem
            </button>
        </div>

    </form>
</div>
@endsection

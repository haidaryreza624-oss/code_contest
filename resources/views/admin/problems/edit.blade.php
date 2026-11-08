@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h2>Edit Problem</h2>
        <p class="text-muted">{{ $problem->title }}</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST"
          action="{{ route('admin.problems.update', $problem) }}">

        @csrf
        @method('PUT')

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                Basic Information
            </div>

            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text"
                           name="title"
                           class="form-control"
                           value="{{ old('title', $problem->title) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                              class="form-control"
                              rows="7"
                              required>{{ old('description', $problem->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Input Format</label>
                    <textarea name="input_format"
                              class="form-control"
                              rows="4"
                              required>{{ old('input_format', $problem->input_format) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Output Format</label>
                    <textarea name="output_format"
                              class="form-control"
                              rows="4"
                              required>{{ old('output_format', $problem->output_format) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Constraints</label>
                    <textarea name="constraints"
                              class="form-control"
                              rows="4"
                              required>{{ old('constraints', $problem->constraints) }}</textarea>
                </div>

            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                Problem Configuration
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Difficulty</label>

                        <select name="difficulty" class="form-select">
                            @foreach(['easy', 'medium', 'hard'] as $difficulty)
                                <option value="{{ $difficulty }}"
                                    @selected(old('difficulty', $problem->difficulty) === $difficulty)>
                                    {{ ucfirst($difficulty) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Time Limit (ms)</label>

                        <input type="number"
                               name="time_limit_ms"
                               class="form-control"
                               value="{{ old('time_limit_ms', $problem->time_limit_ms) }}"
                               min="100"
                               max="60000">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Memory Limit (MB)</label>

                        <input type="number"
                               name="memory_limit_mb"
                               class="form-control"
                               value="{{ old('memory_limit_mb', $problem->memory_limit_mb) }}"
                               min="16"
                               max="1024">
                    </div>

                </div>

            </div>
        </div>

        <div class="d-flex justify-content-between">

            <a href="{{ route('admin.problems.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>

            <button type="submit" class="btn btn-primary">
                Save Changes
            </button>

        </div>

    </form>

</div>
@endsection

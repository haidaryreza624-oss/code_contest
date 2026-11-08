@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="mb-3">
        <a href="{{ route('admin.problems.index') }}"
           class="btn btn-sm btn-outline-secondary">
            &larr; Back to Problems
        </a>
    </div>

    <div class="card shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h3 class="mb-0">
                    {{ $problem->title }}
                </h3>

                <span class="badge bg-secondary">
                    {{ ucfirst($problem->difficulty) }}
                </span>

            </div>

        </div>

        <div class="card-body">

            {{-- Description --}}
            <section class="mb-4">

                <h5>Description</h5>

                <div class="mt-2">
                    {!! nl2br(e($problem->description)) !!}
                </div>

            </section>


            {{-- Input Format --}}
            <section class="mb-4">

                <h5>Input Format</h5>

                <div>
                    {!! nl2br(e($problem->input_format)) !!}
                </div>

            </section>


            {{-- Output Format --}}
            <section class="mb-4">

                <h5>Output Format</h5>

                <div>
                    {!! nl2br(e($problem->output_format)) !!}
                </div>

            </section>


            {{-- Constraints --}}
            <section class="mb-4">

                <h5>Constraints</h5>

                <div>
                    {!! nl2br(e($problem->constraints)) !!}
                </div>

            </section>


            {{-- Sample Test Cases --}}
            <section class="mb-4">

                <h5>Sample Test Cases</h5>

                @forelse($sampleTestCases as $testCase)

                    <div class="border rounded p-3 mb-3">

                        <h6>
                            Example {{ $loop->iteration }}
                        </h6>

                        <div class="mb-3">

                            <strong>Input:</strong>

                            <pre class="bg-light border rounded p-3 mt-2 mb-0">{{ $testCase->input }}</pre>

                        </div>

                        <div>

                            <strong>Expected Output:</strong>

                            <pre class="bg-light border rounded p-3 mt-2 mb-0">{{ $testCase->expected_output }}</pre>

                        </div>

                    </div>

                @empty

                    <div class="alert alert-warning">
                        No sample test cases are available.
                    </div>

                @endforelse

            </section>


            {{-- Limits --}}
            <section class="border-top pt-3">

                <div class="row">

                    <div class="col-md-6">
                        <strong>Time Limit:</strong>
                        {{ $problem->time_limit_ms }} ms
                    </div>

                    <div class="col-md-6">
                        <strong>Memory Limit:</strong>
                        {{ $problem->memory_limit_mb }} MB
                    </div>

                </div>

            </section>

        </div>

    </div>

</div>

@endsection

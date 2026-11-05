@extends('layouts.app')

@section('title', 'Administrator Login | CodeArena')

@section('content')

<div class="row justify-content-center mt-5">

    <div class="col-md-5">

        <div class="card shadow-sm border-0">

            <div class="card-body p-4">

                <h3 class="text-center fw-bold mb-2">
                    CodeArena
                </h3>

                <p class="text-center text-muted mb-4">
                    Administrator Login
                </p>

                <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>

                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Login
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection

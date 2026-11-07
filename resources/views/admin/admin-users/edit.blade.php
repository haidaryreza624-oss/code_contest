@extends('layouts.app')

@section('title', 'Add Administrator')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Add Administrator</h2>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.admin-users.update') }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" value="{{ $id }}">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ $name }}"
                        required
                    >
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ $email }}"
                        required
                    >
                    @error('email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input
                        type="password"
                        name="password"
                        class="form-control"

                    >
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"

                    >
                </div>



                <button type="submit" class="btn btn-primary">
                    Create Administrator
                </button>

                <a href="{{ route('admin.admin-users.index') }}"
                   class="btn btn-secondary">
                    Cancel
                </a>

            </form>

        </div>
    </div>

</div>
@endsection

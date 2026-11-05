@extends('layouts.app')

@section('title', 'Admin Dashboard | CodeArena')

@section('content')

<div class="mb-4">
    <h2 class="fw-bold">Administrator Dashboard</h2>

    <p class="text-muted">
        Welcome to the CodeArena administration panel.
    </p>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <h5 class="card-title">Welcome, {{ auth()->user()->name }}</h5>

        <p class="text-muted mb-0">
            You are logged in as a CodeArena administrator.
        </p>

    </div>
</div>

<form method="POST" action="{{ route('admin.logout') }}" class="mt-3">
    @csrf

    <button type="submit" class="btn btn-outline-danger">
        Logout
    </button>
</form>

@endsection

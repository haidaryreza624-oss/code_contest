@extends('layouts.app')

@section('title', 'Institution Settings')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Institution Settings</h2>



    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Institution Name</label>
                    <input type="text"
                           name="institution_name"
                           class="form-control"
                           value="{{ old('institution_name', $settings?->institution_name) }}"
                           required>
                    @error('institution_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact Email</label>
                    <input type="email"
                           name="contact_email"
                           class="form-control"
                           value="{{ old('contact_email', $settings?->contact_email) }}">
                    @error('contact_email')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact Phone</label>
                    <input type="text"
                           name="contact_phone"
                           class="form-control"
                           value="{{ old('contact_phone', $settings?->contact_phone) }}">
                    @error('contact_phone')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address"
                              class="form-control"
                              rows="3">{{ old('address', $settings?->address) }}</textarea>
                    @error('address')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Website</label>
                    <input type="url"
                           name="website"
                           class="form-control"
                           value="{{ old('website', $settings?->website) }}">
                    @error('website')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Save Settings
                </button>

            </form>

        </div>
    </div>

</div>
@endsection

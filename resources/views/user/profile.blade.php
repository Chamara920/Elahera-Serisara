@extends('layouts.app')

@section('title', 'My Profile - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                
                <div class="card card-custom border-0 shadow-sm p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 pb-4 mb-4 border-bottom">
                        <div class="bg-success text-white rounded-circle p-3 d-flex align-items-center justify-content-center fs-3 fw-bold" style="width: 64px; height: 64px;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="fw-bold text-dark mb-0">{{ $user->name }}</h3>
                            <div class="text-muted small">{{ $user->email }}</div>
                            <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-success' }} mt-1">
                                {{ $user->isAdmin() ? 'System Administrator' : 'Registered Community Member' }}
                            </span>
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark mb-3">Edit Profile Details</h5>

                    <form action="{{ route('user.profile.update') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Email Address (Read Only)</label>
                            <input type="email" class="form-control bg-light" value="{{ $user->email }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Phone Number</label>
                            <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-dark">Bio</label>
                            <textarea name="bio" class="form-control" rows="3">{{ old('bio', $user->bio) }}</textarea>
                        </div>

                        <hr class="my-4">
                        <h6 class="fw-bold text-dark mb-2">Change Password (Leave blank to keep unchanged)</h6>

                        <div class="row g-2 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small text-muted">New Password</label>
                                <input type="password" name="password" class="form-control" placeholder="••••••••">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-custom px-4 fw-bold">
                            <i class="bi bi-save me-1"></i> Save Changes
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection

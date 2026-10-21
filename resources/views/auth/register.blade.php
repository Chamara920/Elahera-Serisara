@extends('layouts.app')

@section('title', 'Register - Community Member - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-custom border-0 shadow p-4 p-md-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.svg') }}" alt="Elahera Sirisara" height="50" class="mb-3">
                        <h3 class="fw-bold text-dark mb-1">Create Community Account</h3>
                        <p class="text-secondary small mb-0">Save custom trips, write blog stories, and participate in traveler forums</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Chamara Premasinghe" value="{{ old('name') }}" required>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required>
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Phone Number (Optional)</label>
                            <input type="tel" name="phone" class="form-control" placeholder="+94 7X XXX XXXX" value="{{ old('phone') }}">
                            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-dark">Short Bio / Interests (Optional)</label>
                            <textarea name="bio" class="form-control" rows="2" placeholder="Tell the community about your travel hobbies or local expertise...">{{ old('bio') }}</textarea>
                            @error('bio') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fw-bold shadow-sm mb-3">
                            <i class="bi bi-person-check-fill me-1"></i> Register as Member
                        </button>
                    </form>

                    <div class="text-center pt-3 border-top small text-muted">
                        Already have an account? <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none">Sign in here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

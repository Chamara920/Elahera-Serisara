@extends('layouts.app')

@section('title', 'Sign In - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card card-custom border-0 shadow p-4 p-md-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.svg') }}" alt="Elahera Sirisara" height="50" class="mb-3">
                        <h3 class="fw-bold text-dark mb-1">Welcome Back</h3>
                        <p class="text-secondary small mb-0">Sign in to your Elahera Sirisara Community account</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                <label class="form-check-label small text-secondary" for="rememberMe">
                                    Remember me
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fw-bold shadow-sm mb-3">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                        </button>
                    </form>

                    <div class="text-center pt-3 border-top small text-muted">
                        Don't have an account yet? <a href="{{ route('register') }}" class="text-success fw-bold text-decoration-none">Register here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

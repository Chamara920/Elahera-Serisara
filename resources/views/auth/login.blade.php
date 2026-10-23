@extends('layouts.app')

@section('title', __('messages.sign_in_title') . ' - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card card-custom border-0 shadow p-4 p-md-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Elahera Serisara" height="54" class="mb-3 rounded-2 shadow-sm object-fit-contain">
                        <h3 class="fw-bold text-dark mb-1">{{ __('messages.welcome_back') }}</h3>
                        <p class="text-secondary small mb-0">{{ __('messages.sign_in_subtitle') }}</p>
                    </div>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">{{ __('messages.email_address') }}</label>
                            <input type="email" name="email" class="form-control" placeholder="{{ __('messages.email_placeholder') }}" value="{{ old('email') }}" required autofocus>
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">{{ __('messages.password') }}</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                <label class="form-check-label small text-secondary" for="rememberMe">
                                    {{ __('messages.remember_me') }}
                                </label>
                            </div>
                            <a href="{{ route('password.forgot') }}" class="small text-success text-decoration-none fw-semibold">
                                <i class="bi bi-key me-1"></i> {{ __('messages.forgot_password_link') }}
                            </a>
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fw-bold shadow-sm mb-3">
                            <i class="bi bi-box-arrow-in-right me-1"></i> {{ __('messages.sign_in_title') }}
                        </button>
                    </form>

                    <div class="text-center pt-3 border-top small text-muted">
                        {{ __('messages.dont_have_account') }} <a href="{{ route('register') }}" class="text-success fw-bold text-decoration-none">{{ __('messages.register_here') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

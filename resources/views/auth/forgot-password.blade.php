@extends('layouts.app')

@section('title', 'Reset Password - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card card-custom border-0 shadow-sm p-4 p-md-5 rounded-4">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Elahera Serisara" height="54" class="mb-3 rounded-2 shadow-sm object-fit-contain">
                        <h3 class="fw-bold text-dark mb-1">Reset Password</h3>
                        <p class="text-secondary small mb-0">Recover your account using your registered security question</p>
                    </div>

                    @if(!isset($question) || empty($question))
                        <!-- STEP 1: Enter Account Email -->
                        <form action="{{ route('password.find') }}" method="POST">
                            @csrf
                            <div class="alert alert-info border-0 rounded-3 small d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                                <div>
                                    Enter your registered community member email address to retrieve your security question.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-dark">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0" placeholder="name@example.com" value="{{ old('email', $email ?? '') }}" required autofocus>
                                </div>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fw-bold shadow-sm mb-3">
                                <i class="bi bi-search me-1"></i> Find Account & Security Question
                            </button>
                        </form>
                    @else
                        <!-- STEP 2: Answer Security Question & Enter New Password -->
                        <form action="{{ route('password.reset.security') }}" method="POST">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="alert alert-success border-0 rounded-3 small d-flex align-items-start gap-2 mb-3">
                                <i class="bi bi-shield-check fs-5 text-success"></i>
                                <div>
                                    Account verified for <strong>{{ $email }}</strong>. Please answer your question below to set a new password.
                                </div>
                            </div>

                            @php
                                $allQuestions = \App\Models\User::defaultSecurityQuestions();
                                $questionText = isset($allQuestions[$question]) ? (app()->getLocale() === 'si' ? $allQuestions[$question]['si'] . ' (' . $allQuestions[$question]['en'] . ')' : $allQuestions[$question]['en'] . ' (' . $allQuestions[$question]['si'] . ')') : $question;
                            @endphp

                            <div class="card bg-light border-0 p-3 mb-3 rounded-3">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    <i class="bi bi-question-circle text-primary me-1"></i> Security Question:
                                </label>
                                <div class="text-primary-emerald fw-semibold small mb-2">{{ $questionText }}</div>

                                <label class="form-label small fw-semibold text-secondary">Your Secret Answer</label>
                                <input type="text" name="security_answer" class="form-control" placeholder="Enter your secret answer" required autofocus>
                                @error('security_answer') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-dark">New Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required>
                                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-dark">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password" required>
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fw-bold shadow-sm mb-2">
                                <i class="bi bi-check2-circle me-1"></i> Reset Password & Sign In
                            </button>

                            <div class="text-center mt-2">
                                <a href="{{ route('password.forgot') }}" class="small text-muted text-decoration-none">
                                    <i class="bi bi-arrow-left"></i> Use a different email
                                </a>
                            </div>
                        </form>
                    @endif

                    <div class="text-center pt-3 border-top small text-muted mt-3">
                        Remembered your password? <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

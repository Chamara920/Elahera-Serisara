@extends('layouts.app')

@section('title', __('messages.reset_password_title') . ' - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card card-custom border-0 shadow-sm p-4 p-md-5 rounded-4">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Elahera Serisara" height="54" class="mb-3 rounded-2 shadow-sm object-fit-contain">
                        <h3 class="fw-bold text-dark mb-1">{{ __('messages.reset_password_title') }}</h3>
                        <p class="text-secondary small mb-0">{{ __('messages.reset_password_subtitle') }}</p>
                    </div>

                    @if(!isset($question) || empty($question))
                        <!-- STEP 1: Enter Account Email -->
                        <form action="{{ route('password.find') }}" method="POST">
                            @csrf
                            <div class="alert alert-info border-0 rounded-3 small d-flex align-items-center gap-2 mb-3">
                                <i class="bi bi-info-circle-fill fs-5 text-info"></i>
                                <div>
                                    {{ __('messages.find_account_heading') }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-dark">{{ __('messages.email_address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0" placeholder="{{ __('messages.email_placeholder') }}" value="{{ old('email', $email ?? '') }}" required autofocus>
                                </div>
                                @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fw-bold shadow-sm mb-3">
                                <i class="bi bi-search me-1"></i> {{ __('messages.find_account_btn') }}
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
                                    {{ __('messages.account_verified_heading', ['email' => $email]) }}
                                </div>
                            </div>

                            @php
                                $locale = app()->getLocale();
                                $allQuestions = \App\Models\User::defaultSecurityQuestions();
                                $questionText1 = isset($allQuestions[$question]) ? ($locale === 'si' ? $allQuestions[$question]['si'] : $allQuestions[$question]['en']) : $question;
                                $questionText2 = (!empty($question2) && isset($allQuestions[$question2])) ? ($locale === 'si' ? $allQuestions[$question2]['si'] : $allQuestions[$question2]['en']) : ($question2 ?? null);
                            @endphp

                            <!-- Security Question 1 -->
                            <div class="card bg-light border-0 p-3 mb-3 rounded-3">
                                <label class="form-label small fw-bold text-dark mb-1">
                                    <span class="badge bg-success me-1">1</span> {{ __('messages.security_question_1') }}:
                                </label>
                                <div class="text-primary-emerald fw-semibold small mb-2">{{ $questionText1 }}</div>

                                <label class="form-label small fw-semibold text-secondary">{{ __('messages.security_answer_1') }}</label>
                                <input type="text" name="security_answer" class="form-control" placeholder="{{ __('messages.security_answer_placeholder') }}" required autofocus>
                                @error('security_answer') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            @if(!empty($questionText2))
                                <!-- Security Question 2 -->
                                <div class="card bg-light border-0 p-3 mb-3 rounded-3">
                                    <label class="form-label small fw-bold text-dark mb-1">
                                        <span class="badge bg-primary me-1">2</span> {{ __('messages.security_question_2') }}:
                                    </label>
                                    <div class="text-primary-emerald fw-semibold small mb-2">{{ $questionText2 }}</div>

                                    <label class="form-label small fw-semibold text-secondary">{{ __('messages.security_answer_2') }}</label>
                                    <input type="text" name="security_answer_2" class="form-control" placeholder="{{ __('messages.security_answer_placeholder') }}" required>
                                    @error('security_answer_2') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label small fw-semibold text-dark">{{ __('messages.new_password') }}</label>
                                <input type="password" name="password" class="form-control" placeholder="{{ __('messages.password_placeholder') }}" required>
                                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-dark">{{ __('messages.confirm_new_password') }}</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('messages.confirm_password_placeholder') }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fw-bold shadow-sm mb-2">
                                <i class="bi bi-check2-circle me-1"></i> {{ __('messages.reset_pwd_btn') }}
                            </button>

                            <div class="text-center mt-2">
                                <a href="{{ route('password.forgot') }}" class="small text-muted text-decoration-none">
                                    <i class="bi bi-arrow-left"></i> {{ __('messages.use_different_email') }}
                                </a>
                            </div>
                        </form>
                    @endif

                    <div class="text-center pt-3 border-top small text-muted mt-3">
                        {{ __('messages.remembered_password') }} <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none">{{ __('messages.back_to_login') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

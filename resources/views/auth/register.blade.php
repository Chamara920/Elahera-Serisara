@extends('layouts.app')

@section('title', __('messages.register_title') . ' - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-custom border-0 shadow p-4 p-md-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Elahera Serisara" height="54" class="mb-3 rounded-2 shadow-sm object-fit-contain">
                        <h3 class="fw-bold text-dark mb-1">{{ __('messages.create_community_account') }}</h3>
                        <p class="text-secondary small mb-0">{{ __('messages.register_subtitle') }}</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">{{ __('messages.full_name') }}</label>
                            <input type="text" name="name" class="form-control" placeholder="{{ __('messages.full_name_placeholder') }}" value="{{ old('name') }}" required>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">{{ __('messages.email_address') }}</label>
                            <input type="email" name="email" class="form-control" placeholder="{{ __('messages.email_placeholder') }}" value="{{ old('email') }}" required>
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">{{ __('messages.password') }}</label>
                                <input type="password" name="password" class="form-control" placeholder="{{ __('messages.password_placeholder') }}" required>
                                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">{{ __('messages.confirm_password') }}</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="{{ __('messages.confirm_password_placeholder') }}" required>
                            </div>
                        </div>

                        <!-- Security Questions for Password Recovery (2 Questions) -->
                        <div class="card bg-light border-0 p-3 mb-3 rounded-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-shield-lock-fill text-success"></i>
                                <span class="fw-bold text-dark small">{{ __('messages.security_question_heading') }}</span>
                            </div>

                            @php
                                $locale = app()->getLocale();
                                $questions = $securityQuestions ?? \App\Models\User::defaultSecurityQuestions();
                            @endphp

                            <!-- Question 1 -->
                            <div class="mb-3 p-2 bg-white rounded-2 border">
                                <label class="form-label small fw-semibold text-dark mb-1">
                                    <span class="badge bg-success me-1">1</span> {{ __('messages.security_question_1') }}
                                </label>
                                <select name="security_question" id="security_question_1" class="form-select form-select-sm mb-2" required>
                                    <option value="" disabled {{ old('security_question') ? '' : 'selected' }}>{{ __('messages.choose_question_placeholder') }}</option>
                                    @foreach($questions as $key => $q)
                                        <option value="{{ $key }}" {{ old('security_question') === $key ? 'selected' : '' }}>
                                            {{ $locale === 'si' ? $q['si'] : $q['en'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('security_question') <div class="text-danger small mb-1">{{ $message }}</div> @enderror

                                <input type="text" name="security_answer" class="form-control form-control-sm" placeholder="{{ __('messages.security_answer_1') }}" value="{{ old('security_answer') }}" required>
                                @error('security_answer') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <!-- Question 2 -->
                            <div class="p-2 bg-white rounded-2 border">
                                <label class="form-label small fw-semibold text-dark mb-1">
                                    <span class="badge bg-primary me-1">2</span> {{ __('messages.security_question_2') }}
                                </label>
                                <select name="security_question_2" id="security_question_2" class="form-select form-select-sm mb-2" required>
                                    <option value="" disabled {{ old('security_question_2') ? '' : 'selected' }}>{{ __('messages.choose_question_placeholder') }}</option>
                                    @foreach($questions as $key => $q)
                                        <option value="{{ $key }}" {{ old('security_question_2') === $key ? 'selected' : '' }}>
                                            {{ $locale === 'si' ? $q['si'] : $q['en'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('security_question_2') <div class="text-danger small mb-1">{{ $message }}</div> @enderror

                                <input type="text" name="security_answer_2" class="form-control form-control-sm" placeholder="{{ __('messages.security_answer_2') }}" value="{{ old('security_answer_2') }}" required>
                                @error('security_answer_2') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-text text-muted mt-2" style="font-size: 0.72rem;">{{ __('messages.security_answer_help') }}</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-dark">{{ __('messages.bio_interests_optional') }}</label>
                            <textarea name="bio" class="form-control" rows="2" placeholder="{{ __('messages.bio_placeholder') }}">{{ old('bio') }}</textarea>
                            @error('bio') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fw-bold shadow-sm mb-3">
                            <i class="bi bi-person-check-fill me-1"></i> {{ __('messages.register_as_member') }}
                        </button>
                    </form>

                    <div class="text-center pt-3 border-top small text-muted">
                        {{ __('messages.already_have_account') }} <a href="{{ route('login') }}" class="text-success fw-bold text-decoration-none">{{ __('messages.sign_in_here') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

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

                        <!-- Security Question for Password Recovery -->
                        <div class="card bg-light border-0 p-3 mb-3 rounded-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-shield-lock-fill text-success"></i>
                                <span class="fw-bold text-dark small">{{ __('messages.security_question_heading') }}</span>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small text-secondary">{{ __('messages.select_security_question') }}</label>
                                <select name="security_question" class="form-select form-select-sm" required>
                                    <option value="" disabled {{ old('security_question') ? '' : 'selected' }}>{{ __('messages.choose_question_placeholder') }}</option>
                                    @php
                                        $locale = app()->getLocale();
                                        $questions = $securityQuestions ?? \App\Models\User::defaultSecurityQuestions();
                                    @endphp
                                    @foreach($questions as $key => $q)
                                        <option value="{{ $key }}" {{ old('security_question') === $key ? 'selected' : '' }}>
                                            {{ $locale === 'si' ? $q['si'] : $q['en'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('security_question') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label small text-secondary">{{ __('messages.security_answer_label') }}</label>
                                <input type="text" name="security_answer" class="form-control form-control-sm" placeholder="{{ __('messages.security_answer_placeholder') }}" value="{{ old('security_answer') }}" required>
                                <div class="form-text text-muted" style="font-size: 0.72rem;">{{ __('messages.security_answer_help') }}</div>
                                @error('security_answer') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-dark">{{ __('messages.phone_number_optional') }}</label>
                            <input type="tel" name="phone" class="form-control" placeholder="{{ __('messages.phone_placeholder') }}" value="{{ old('phone') }}">
                            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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

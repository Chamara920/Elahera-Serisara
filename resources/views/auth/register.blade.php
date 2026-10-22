@extends('layouts.app')

@section('title', 'Register - Community Member - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-custom border-0 shadow p-4 p-md-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/logo.jpg') }}" alt="Elahera Serisara" height="54" class="mb-3 rounded-2 shadow-sm object-fit-contain">
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

                        <!-- Security Question for Password Recovery -->
                        <div class="card bg-light border-0 p-3 mb-3 rounded-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-shield-lock-fill text-success"></i>
                                <span class="fw-bold text-dark small">Security Question for Password Recovery (මුරපදය නැවත ලබාගැනීමට ආරක්ෂක ප්‍රශ්නය)</span>
                            </div>
                            <div class="mb-2">
                                <label class="form-label small text-secondary">Select a Security Question</label>
                                <select name="security_question" class="form-select form-select-sm" required>
                                    <option value="" disabled {{ old('security_question') ? '' : 'selected' }}>-- Choose a Question / ආරක්ෂක ප්‍රශ්නයක් තෝරන්න --</option>
                                    @php
                                        $locale = app()->getLocale();
                                        $questions = $securityQuestions ?? \App\Models\User::defaultSecurityQuestions();
                                    @endphp
                                    @foreach($questions as $key => $q)
                                        <option value="{{ $key }}" {{ old('security_question') === $key ? 'selected' : '' }}>
                                            {{ $locale === 'si' ? $q['si'] . ' (' . $q['en'] . ')' : $q['en'] . ' (' . $q['si'] . ')' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('security_question') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label small text-secondary">Your Answer (ඔබේ පිළිතුර)</label>
                                <input type="text" name="security_answer" class="form-control form-control-sm" placeholder="e.g. Mahasen Central College" value="{{ old('security_answer') }}" required>
                                <div class="form-text text-muted" style="font-size: 0.72rem;">Remember this answer; it will be required if you ever need to reset your password.</div>
                                @error('security_answer') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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

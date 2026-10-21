@extends('layouts.app')

@section('title', 'Ask a Question - Forum - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="mb-3">
                    <a href="{{ route('forum.index') }}" class="text-success text-decoration-none small fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Back to Forum
                    </a>
                </div>

                <div class="card card-custom border-0 shadow-sm p-4 p-md-5">
                    <h2 class="fw-bold text-dark mb-1">Ask a Question / Start a Discussion</h2>
                    <p class="text-secondary small mb-4">
                        Post your query regarding Elahera tourist destinations, bus timetables, camping spots, or local guidance.
                    </p>

                    <form action="{{ route('forum.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Question Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Is it safe to bathe at Heeratiya Oya cascades after heavy rains?" value="{{ old('title') }}" required>
                            @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Channel Category</label>
                            <select name="forum_category_id" class="form-select" required>
                                <option value="">-- Choose Forum Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('forum_category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->getLocalizedName() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('forum_category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark small">Details & Specific Context</label>
                            <textarea name="content" class="form-control" rows="6" placeholder="Provide details like expected travel dates, vehicle type, group size, or specific concerns..." required>{{ old('content') }}</textarea>
                            @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('forum.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary-custom px-4 fw-bold">
                                <i class="bi bi-send me-1"></i> Publish Question
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection

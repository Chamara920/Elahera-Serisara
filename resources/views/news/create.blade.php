@extends('layouts.app')

@section('title', 'Submit News or Event - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="mb-3">
                    <a href="{{ route('news.index') }}" class="text-success text-decoration-none small fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Back to News & Events
                    </a>
                </div>

                <div class="card card-custom border-0 shadow-sm p-4 p-md-5">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-danger">Community Notice</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-1">Submit News or Upcoming Event</h2>
                    <p class="text-secondary small mb-4">
                        Share upcoming festivals, community tree planting, road notices, or regional alerts.
                    </p>

                    <div class="alert alert-warning border-warning-subtle small mb-4 d-flex align-items-start gap-2">
                        <i class="bi bi-hourglass-split text-warning fs-5 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong>Admin Approval Required:</strong> All submitted news and events are vetted by system administrators before being published on the main site.
                        </div>
                    </div>

                    <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold text-dark small">Title</label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Elahera Annual Harvest Festival 2026" value="{{ old('title') }}" required>
                                @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-dark small">Submission Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="news" {{ old('type') == 'news' ? 'selected' : '' }}>News Notice</option>
                                    <option value="event" {{ old('type') == 'event' ? 'selected' : '' }}>Public Event</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Date (If applicable)</label>
                                <input type="date" name="event_date" class="form-control" value="{{ old('event_date') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark small">Location / Venue</label>
                                <input type="text" name="location" class="form-control" placeholder="e.g. Bakamuna Mahaweli Stadium" value="{{ old('location') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Featured Image (Optional)</label>
                            <input type="file" name="featured_image" class="form-control" accept="image/*">
                            @error('featured_image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark small">Full Details & Notice Description</label>
                            <textarea name="content" class="form-control" rows="8" placeholder="Provide event timings, entry criteria, background, and organizing contacts..." required>{{ old('content') }}</textarea>
                            @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('news.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary-custom px-4 fw-bold">
                                <i class="bi bi-send me-1"></i> Submit for Approval
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection

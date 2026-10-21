@extends('layouts.app')

@section('title', 'Write a Community Blog Post - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="mb-3">
                    <a href="{{ route('blog.index') }}" class="text-success text-decoration-none small fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Back to Blog
                    </a>
                </div>

                <div class="card card-custom border-0 shadow-sm p-4 p-md-5">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-success">Community Contribution</span>
                    </div>
                    <h2 class="fw-bold text-dark mb-1">Write a Travel Blog Post</h2>
                    <p class="text-secondary small mb-4">
                        Share your unique travel experience, transportation tips, or photographic guide of the Elahera region.
                    </p>

                    <!-- Notice about Admin Approval -->
                    <div class="alert alert-info border-info-subtle small mb-4 d-flex align-items-start gap-2">
                        <i class="bi bi-info-circle-fill text-info fs-5 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <strong>Moderation Notice:</strong> To ensure accurate travel guidelines and prevent inappropriate content, community articles are sent for Administrator review before being published publicly.
                        </div>
                    </div>

                    <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Article Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Cycling Along the Ancient Elahera Yodha Ela Canal" value="{{ old('title') }}" required>
                            @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Category</label>
                            <select name="category_id" class="form-select" required>
                                <option value="">-- Choose Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->getLocalizedName() }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Featured Image (Optional)</label>
                            <input type="file" name="featured_image" class="form-control" accept="image/*">
                            <div class="form-text small">Accepted formats: JPG, PNG, WEBP. Max size: 3MB.</div>
                            @error('featured_image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark small">Article Content</label>
                            <textarea name="content" class="form-control" rows="10" placeholder="Write your detailed travelogue, directions, recommendations, and tips..." required>{{ old('content') }}</textarea>
                            @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary-custom px-4 fw-bold">
                                <i class="bi bi-send me-1"></i> Submit for Review
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection

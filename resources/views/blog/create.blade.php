@extends('layouts.app')

@section('title', 'Write a Community Blog Post - ' . __('messages.brand_name'))

@push('styles')
    <!-- Trix Editor CSS (Matching Create Destination Rich Editor) -->
    <link rel="stylesheet" href="{{ asset('vendor/trix/trix.css') }}">
    <style>
        trix-toolbar {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            border-bottom: none;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            padding: 8px 10px;
        }
        trix-toolbar .trix-button-group {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background: #ffffff;
            margin-bottom: 4px;
        }
        trix-toolbar .trix-button {
            border-bottom: none;
            padding: 4px 8px;
        }
        trix-toolbar .trix-button.trix-active {
            background: #ecfdf5;
            color: #059669;
        }
        trix-editor {
            min-height: 260px;
            max-height: 520px;
            overflow-y: auto;
            border: 1px solid #cbd5e1 !important;
            border-top: none !important;
            border-bottom-left-radius: 8px !important;
            border-bottom-right-radius: 8px !important;
            background-color: #ffffff;
            font-size: 0.95rem;
            line-height: 1.7;
            padding: 14px 16px;
        }
        trix-editor:focus {
            border-color: #10b981 !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15) !important;
            outline: none !important;
        }
        trix-editor.trix-content h1 {
            font-size: 1.35rem;
            font-weight: 700;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }
        trix-editor.trix-content blockquote {
            border-left: 3px solid #10b981;
            padding-left: 12px;
            color: #475569;
            font-style: italic;
        }
        trix-editor.trix-content pre {
            background: #f1f5f9;
            padding: 10px;
            border-radius: 6px;
        }
    </style>
@endpush

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

                        <div class="row g-3 mb-3">
                            <div class="col-md-7">
                                <label class="form-label fw-semibold text-dark small">{{ __('messages.select_category') }}</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- {{ __('messages.select_category') }} --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->getLocalizedName() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold text-dark small">{{ __('messages.post_language') }}</label>
                                <select name="language" class="form-select">
                                    <option value="all" {{ old('language', 'all') === 'all' ? 'selected' : '' }}>{{ __('messages.lang_all') }}</option>
                                    <option value="en" {{ old('language') === 'en' ? 'selected' : '' }}>{{ __('messages.lang_en') }}</option>
                                    <option value="si" {{ old('language') === 'si' ? 'selected' : '' }}>{{ __('messages.lang_si') }}</option>
                                </select>
                                @error('language') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Featured Image (Optional)</label>
                            <input type="file" name="featured_image" class="form-control" accept="image/*">
                            <div class="form-text small">Accepted formats: JPG, PNG, WEBP. Max size: 3MB.</div>
                            @error('featured_image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark small">Article Content <span class="text-danger">*</span></label>
                            <input id="article_content" type="hidden" name="content" value="{{ old('content') }}">
                            <trix-editor input="article_content" class="trix-content" placeholder="Write your detailed travelogue, directions, recommendations, and tips..."></trix-editor>
                            @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Optional Sinhala Translation Fields -->
                        <div class="card bg-light border-0 p-3 mb-4 rounded-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-translate text-success"></i>
                                <span class="fw-bold text-dark small">Publish in Sinhala as well (Optional)</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark small">{{ __('messages.title_si_optional') }}</label>
                                <input type="text" name="title_si" class="form-control" placeholder="{{ __('messages.title_si_placeholder') }}" value="{{ old('title_si') }}">
                                @error('title_si') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div>
                                <label class="form-label fw-semibold text-dark small">{{ __('messages.content_si_optional') }}</label>
                                <input id="article_content_si" type="hidden" name="content_si" value="{{ old('content_si') }}">
                                <trix-editor input="article_content_si" class="trix-content" placeholder="{{ __('messages.content_si_placeholder') }}"></trix-editor>
                                @error('content_si') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
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

@push('scripts')
    <!-- Trix Editor JS (Local Asset) -->
    <script src="{{ asset('vendor/trix/trix.umd.min.js') }}"></script>
    <script>
        document.addEventListener('trix-file-accept', function(e) {
            // Prevent drag and drop file uploads inside the text editor
            e.preventDefault();
        });
    </script>
@endpush

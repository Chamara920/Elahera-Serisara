@extends('layouts.app')

@section('title', 'Search Results for "' . $query . '" - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        
        <div class="mb-4">
            <span class="badge bg-success text-white px-3 py-1.5 rounded-pill mb-2">Search Results</span>
            <h1 class="display-6 fw-bold text-dark mb-1">Results for "{{ $query }}"</h1>
            <p class="text-secondary small mb-0">Found {{ $destinations->count() }} destinations, {{ $blogs->count() }} blog articles, and {{ $news->count() }} announcements.</p>
        </div>

        <!-- 1. Destinations Matches -->
        <h4 class="fw-bold text-dark mb-3">
            <i class="bi bi-geo-alt-fill text-success me-1"></i> Destinations ({{ $destinations->count() }})
        </h4>

        <div class="row g-4 row-cols-1 row-cols-md-3 mb-5">
            @forelse($destinations as $dest)
                <div class="col">
                    <div class="card card-custom h-100 p-3">
                        <span class="badge bg-light text-secondary border small align-self-start mb-2">{{ $dest->getLocalizedCategory() }}</span>
                        <h5 class="fw-bold mb-1">
                            <a href="{{ route('destinations.show', $dest->slug) }}" class="text-dark text-decoration-none hover-success">
                                {{ $dest->getLocalizedTitle() }}
                            </a>
                        </h5>
                        <p class="small text-secondary mb-3 flex-grow-1">{{ Str::limit($dest->getLocalizedShortDescription(), 90) }}</p>
                        <a href="{{ route('destinations.show', $dest->slug) }}" class="btn btn-outline-success btn-sm w-100">View Destination</a>
                    </div>
                </div>
            @empty
                <div class="col-12"><p class="text-muted small">No matching destinations found.</p></div>
            @endforelse
        </div>

        <!-- 2. Blog Posts Matches -->
        <h4 class="fw-bold text-dark mb-3">
            <i class="bi bi-journal-text text-primary me-1"></i> Community Blog Posts ({{ $blogs->count() }})
        </h4>

        <div class="row g-4 row-cols-1 row-cols-md-2 mb-5">
            @forelse($blogs as $blog)
                <div class="col">
                    <div class="card card-custom h-100 p-3">
                        <h6 class="fw-bold mb-1">
                            <a href="{{ route('blog.show', $blog->slug) }}" class="text-dark text-decoration-none hover-success">
                                {{ $blog->title }}
                            </a>
                        </h6>
                        <p class="small text-secondary mb-2">{{ Str::limit(strip_tags($blog->content), 120) }}</p>
                        <div class="small text-muted mt-auto">By {{ $blog->user->name }} &bull; {{ $blog->created_at->format('M d, Y') }}</div>
                    </div>
                </div>
            @empty
                <div class="col-12"><p class="text-muted small">No matching blog posts found.</p></div>
            @endforelse
        </div>

    </div>

@endsection

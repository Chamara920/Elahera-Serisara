@extends('layouts.app')

@section('title', 'News & Events - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill mb-2">Regional Updates</span>
                <h1 class="display-6 fw-bold text-dark mb-1">News, Notices & Upcoming Events</h1>
                <p class="text-secondary small mb-0">Seasonal updates, festival announcements, and conservation notices around Elahera and Bakamuna.</p>
            </div>
            <div>
                <a href="{{ route('news.create') }}" class="btn btn-primary-custom fw-bold shadow-sm px-4">
                    <i class="bi bi-megaphone me-1"></i> Submit Notice or Event
                </a>
            </div>
        </div>

        <!-- Filter Pills -->
        <div class="d-flex gap-2 mb-4">
            <a href="{{ route('news.index') }}" class="btn btn-sm rounded-pill px-3 {{ !$type ? 'btn-success text-white' : 'btn-outline-secondary' }}">
                All Updates
            </a>
            <a href="{{ route('news.index', ['type' => 'news']) }}" class="btn btn-sm rounded-pill px-3 {{ $type === 'news' ? 'btn-success text-white' : 'btn-outline-secondary' }}">
                <i class="bi bi-newspaper me-1"></i> News Only
            </a>
            <a href="{{ route('news.index', ['type' => 'event']) }}" class="btn btn-sm rounded-pill px-3 {{ $type === 'event' ? 'btn-success text-white' : 'btn-outline-secondary' }}">
                <i class="bi bi-calendar-event me-1"></i> Events Only
            </a>
        </div>

        <!-- News Grid -->
        <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3 mb-5">
            @forelse($items as $item)
                <div class="col">
                    <div class="card card-custom h-100 d-flex flex-column border-0 shadow-sm overflow-hidden">
                        <div class="position-relative overflow-hidden" style="height: 180px;">
                            <img src="{{ $item->getFeaturedImageUrl() }}" class="card-img-top w-100 h-100 object-fit-cover" alt="{{ $item->title }}">
                            <span class="position-absolute top-0 start-0 m-3 badge {{ $item->type === 'event' ? 'bg-warning text-dark' : 'bg-primary' }} text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                                {{ $item->type }}
                            </span>
                        </div>

                        <div class="card-body p-4 d-flex flex-column">
                            @if($item->event_date)
                                <div class="small text-danger fw-semibold mb-1">
                                    <i class="bi bi-calendar-check me-1"></i> Date: {{ $item->event_date->format('M d, Y') }}
                                </div>
                            @endif

                            @if($item->location)
                                <div class="small text-muted mb-2">
                                    <i class="bi bi-geo-alt me-1"></i> {{ $item->location }}
                                </div>
                            @endif

                            <h5 class="fw-bold mb-2">
                                <a href="{{ route('news.show', $item->slug) }}" class="text-dark text-decoration-none hover-success">
                                    {{ $item->title }}
                                </a>
                            </h5>

                            <p class="text-secondary small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ Str::limit($item->content, 110) }}
                            </p>

                            <div class="pt-3 border-top d-flex justify-content-between align-items-center small text-muted">
                                <span><i class="bi bi-eye"></i> {{ $item->views_count }}</span>
                                <a href="{{ route('news.show', $item->slug) }}" class="text-success fw-bold text-decoration-none">
                                    Read Full Notice &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-megaphone fs-1 text-muted d-block mb-2"></i>
                    <h5 class="fw-bold">No announcements found</h5>
                    <p class="text-muted small">Stay tuned for upcoming seasonal notices and regional festivals.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center">
            {{ $items->links('pagination::bootstrap-5') }}
        </div>

    </div>

@endsection

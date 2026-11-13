@extends('layouts.app')

@section('title', $item->title . ' - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                <div class="mb-3">
                    <a href="{{ route('news.index') }}" class="text-success text-decoration-none small fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Back to News & Events
                    </a>
                </div>

                @if(!$item->is_approved)
                    <div class="alert alert-warning d-flex align-items-center mb-4">
                        <i class="bi bi-hourglass-split fs-4 me-2"></i>
                        <div>
                            <strong>Pending Administrator Review:</strong> This notice has been submitted by a community member and is awaiting approval.
                        </div>
                    </div>
                @endif

                <div class="card card-custom border-0 shadow-sm p-4 p-md-5 mb-5">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="badge {{ $item->type === 'event' ? 'bg-warning text-dark' : 'bg-primary' }} text-uppercase">
                            {{ $item->type }}
                        </span>
                        @if($item->category)
                            <span class="badge bg-secondary">
                                {{ $item->category->getLocalizedName() }}
                            </span>
                        @endif
                        @if($item->event_date)
                            <span class="badge bg-danger">Event Date: {{ $item->event_date->format('M d, Y') }}</span>
                        @endif
                        <span class="text-muted small">&bull; {{ $item->created_at->format('M d, Y') }}</span>
                    </div>

                    <h1 class="display-6 fw-bold text-dark mb-3">{{ $item->getLocalizedTitle() }}</h1>

                    @if($item->location)
                        <div class="p-2.5 bg-light rounded-3 text-secondary small mb-4">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> <strong>Location / Venue:</strong> {{ $item->location }}
                        </div>
                    @endif

                    @if($item->featured_image)
                        <div class="mb-4 rounded-3 overflow-hidden" style="max-height: 400px;">
                            <img src="{{ $item->getFeaturedImageUrl() }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->title }}">
                        </div>
                    @endif

                    <div class="fs-6 leading-relaxed text-secondary mb-5 rich-text-content" style="line-height: 1.8;">
                        @php
                            $newsContent = $item->getLocalizedContent();
                        @endphp
                        {!! Str::contains($newsContent, ['<p>', '<br>', '<div>', '<h1>', '<h2>', '<h3>', '<h4>', '<h5>', '<h6>', '<ul>', '<ol>', '<strong>', '<em>', '<li>', '<blockquote>']) 
                            ? $newsContent 
                            : nl2br(e($newsContent)) !!}
                    </div>

                    <!-- Comments Section -->
                    <div class="pt-4 border-top">
                        <h4 class="fw-bold text-dark mb-3">
                            Discussion & Inquiries ({{ $item->comments->count() }})
                        </h4>

                        <div class="d-flex flex-column gap-3 mb-4">
                            @forelse($item->comments as $comment)
                                <div class="p-3 bg-light rounded-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-bold text-dark small">{{ $comment->user->name }}</span>
                                            @if(auth()->check() && auth()->user()->isAdmin())
                                                <form action="{{ route('news.comments.destroy', $comment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm py-0 px-2" style="font-size: 0.72rem;" title="Delete this comment">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                        <span class="text-muted small" style="font-size: 0.72rem;">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="small text-secondary mb-0">{{ $comment->comment }}</p>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">No comments on this notice yet.</p>
                            @endforelse
                        </div>

                        @auth
                            <div class="p-3 bg-light rounded-3 border">
                                <h6 class="fw-bold text-dark mb-2">Leave an Inquiry or Comment</h6>
                                <form action="{{ route('news.comment', $item->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="comment" class="form-control form-control-sm" rows="3" placeholder="Post a comment or inquiry regarding this event..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary-custom btn-sm">
                                        <i class="bi bi-send me-1"></i> Submit Comment
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-light border small text-center mb-0">
                                <i class="bi bi-lock me-1"></i> <a href="{{ route('login') }}" class="fw-bold text-success text-decoration-none">Log in</a> as a Community Member to leave a comment.
                            </div>
                        @endauth
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@extends('layouts.app')

@section('title', $post->title . ' - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        
        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                <div class="mb-3">
                    <a href="{{ route('blog.index') }}" class="text-success text-decoration-none small fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Back to Blog
                    </a>
                </div>

                @if(!$post->is_approved)
                    <div class="alert alert-warning d-flex align-items-center mb-4">
                        <i class="bi bi-hourglass-split fs-4 me-2"></i>
                        <div>
                            <strong>Pending Administrator Approval:</strong> This article is currently awaiting moderation by an Admin before becoming publicly visible to all visitors.
                        </div>
                    </div>
                @endif

                <div class="card card-custom border-0 shadow-sm p-4 p-md-5 mb-5">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="badge bg-success small">{{ $post->category?->name_en ?: 'Travel Article' }}</span>
                        <span class="text-muted small">&bull; {{ $post->created_at->format('F d, Y') }}</span>
                        <span class="text-muted small">&bull; <i class="bi bi-eye"></i> {{ $post->views_count }} views</span>
                    </div>

                    <h1 class="display-6 fw-bold text-dark mb-4">{{ $post->title }}</h1>

                    <!-- Author Info -->
                    <div class="d-flex align-items-center gap-3 pb-4 mb-4 border-bottom">
                        <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center fw-bold" style="width: 46px; height: 46px;">
                            {{ substr($post->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark">{{ $post->user->name }}</div>
                            <div class="small text-muted">{{ $post->user->bio ?: 'Registered Community Contributor' }}</div>
                        </div>
                    </div>

                    @if($post->featured_image)
                        <div class="mb-4 rounded-3 overflow-hidden" style="max-height: 420px;">
                            <img src="{{ file_exists(public_path($post->featured_image)) ? asset($post->featured_image) : asset('images/blog/blog1.svg') }}" class="w-100 h-100 object-fit-cover" alt="{{ $post->title }}">
                        </div>
                    @endif

                    <div class="fs-6 leading-relaxed text-secondary mb-5" style="line-height: 1.9;">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    <!-- Comments Section -->
                    <div class="pt-4 border-top">
                        <h4 class="fw-bold text-dark mb-4">
                            Comments ({{ $post->comments->count() }})
                        </h4>

                        <div class="d-flex flex-column gap-3 mb-4">
                            @forelse($post->comments as $comment)
                                <div class="p-3 bg-light rounded-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <div class="fw-bold text-dark small">{{ $comment->user->name }}</div>
                                        <span class="text-muted small" style="font-size: 0.72rem;">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="small text-secondary mb-0">{{ $comment->comment }}</p>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">No comments yet. Be the first to share your thoughts!</p>
                            @endforelse
                        </div>

                        <!-- Comment Submission Form for Logged In Members -->
                        @auth
                            <div class="p-3 bg-light rounded-3 border">
                                <h6 class="fw-bold text-dark mb-2">Leave a Comment</h6>
                                <form action="{{ route('blog.comment', $post->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="comment" class="form-control form-control-sm" rows="3" placeholder="Share your experience or ask a question about this article..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary-custom btn-sm">
                                        <i class="bi bi-chat-left-dots me-1"></i> Submit Comment
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-light border small text-center mb-0">
                                <i class="bi bi-lock me-1"></i> <a href="{{ route('login') }}" class="fw-bold text-success text-decoration-none">Log in</a> to join the community discussion on this post.
                            </div>
                        @endauth
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection

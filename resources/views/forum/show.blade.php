@extends('layouts.app')

@section('title', $topic->title . ' - Forum - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                <div class="mb-3">
                    <a href="{{ route('forum.index') }}" class="text-success text-decoration-none small fw-semibold">
                        <i class="bi bi-arrow-left me-1"></i> Back to Forum
                    </a>
                </div>

                <!-- Main Topic Card -->
                <div class="card card-custom border-0 shadow-sm p-4 p-md-5 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary small">{{ $topic->category?->getLocalizedName() ?? 'General' }}</span>
                        <span class="small text-muted">{{ $topic->created_at->format('M d, Y h:i A') }}</span>
                    </div>

                    <h2 class="fw-bold text-dark mb-3">{{ $topic->getLocalizedTitle() }}</h2>

                    <div class="d-flex align-items-center gap-2 pb-3 mb-4 border-bottom">
                        <div class="bg-primary text-white rounded-circle p-1 d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">
                            {{ substr($topic->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="fw-semibold text-dark small">{{ $topic->user->name }}</div>
                            <div class="small text-muted" style="font-size: 0.75rem;">Community Traveler</div>
                        </div>
                    </div>

                    <div class="fs-6 text-secondary mb-4 leading-relaxed" style="line-height: 1.8;">
                        {!! nl2br(e($topic->getLocalizedContent())) !!}
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top text-muted small">
                        <span><i class="bi bi-eye"></i> {{ $topic->views_count }} views</span>
                        <span><i class="bi bi-chat-dots-fill text-success"></i> {{ $topic->replies->count() }} responses</span>
                    </div>
                </div>

                <!-- Replies List -->
                <h4 class="fw-bold text-dark mb-3">
                    Replies ({{ $topic->replies->count() }})
                </h4>

                <div class="d-flex flex-column gap-3 mb-4">
                    @forelse($topic->replies as $reply)
                        <div class="card card-custom border-0 shadow-sm p-3 p-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-success text-white rounded-circle p-1 d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ substr($reply->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold small text-dark">{{ $reply->user->name }}</div>
                                        <div class="small text-muted" style="font-size: 0.72rem;">{{ $reply->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="small text-secondary leading-relaxed">
                                {!! nl2br(e($reply->content)) !!}
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-light border text-muted small text-center p-4">
                            No answers yet. Share your experience or tip below!
                        </div>
                    @endforelse
                </div>

                <!-- Reply Form (Registered Users) -->
                @auth
                    <div class="card card-custom border-0 shadow-sm p-4">
                        <h5 class="fw-bold text-dark mb-3">Post a Reply</h5>
                        <form action="{{ route('forum.reply', $topic->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="content" class="form-control" rows="4" placeholder="Type your advice, route tips, or response here..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary-custom fw-bold px-4">
                                <i class="bi bi-reply-fill me-1"></i> Post Reply
                            </button>
                        </form>
                    </div>
                @else
                    <div class="alert alert-light border text-center p-4">
                        <i class="bi bi-lock text-muted fs-4 d-block mb-1"></i>
                        Please <a href="{{ route('login') }}" class="fw-bold text-success text-decoration-none">Log in</a> as a Community Member to post a reply.
                    </div>
                @endauth

            </div>
        </div>
    </div>

@endsection

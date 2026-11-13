@extends('layouts.app')

@section('title', 'Traveler Forum & Community Q&A - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <span class="badge bg-primary text-white px-3 py-1.5 rounded-pill mb-2">Community Q&A</span>
                <h1 class="display-6 fw-bold text-dark mb-1">Traveler Discussion Forum</h1>
                <p class="text-secondary small mb-0">Ask questions about routes, weather, stays, and share real-time tips with fellow explorers.</p>
            </div>
            <div>
                <a href="{{ route('forum.create') }}" class="btn btn-primary-custom fw-bold shadow-sm px-4">
                    <i class="bi bi-question-circle me-1"></i> Ask a Question
                </a>
            </div>
        </div>

        <div class="row g-4">
            
            <!-- Left (2/3 Width): Topics List -->
            <div class="col-lg-8">
                
                <!-- Search & Filters -->
                <div class="card card-custom border-0 shadow-sm p-3 mb-4">
                    <form action="{{ route('forum.index') }}" method="GET">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Search discussion topics..." value="{{ request('search') }}">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </div>
                    </form>
                </div>

                <!-- Topics Listing -->
                <div class="d-flex flex-column gap-3 mb-5">
                    @forelse($topics as $topic)
                        <div class="card card-custom border-0 shadow-sm p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-light text-primary border small">
                                    <i class="bi bi-chat-text me-1"></i> {{ $topic->category?->getLocalizedName() ?? 'General' }}
                                </span>
                                <span class="small text-muted" style="font-size: 0.75rem;">
                                    <i class="bi bi-clock me-1"></i> {{ $topic->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <h5 class="fw-bold mb-2">
                                <a href="{{ route('forum.show', $topic->slug) }}" class="text-dark text-decoration-none hover-success">
                                    {{ $topic->getLocalizedTitle() }}
                                </a>
                            </h5>

                            <p class="text-secondary small mb-3">
                                {{ Str::limit($topic->getLocalizedContent(), 140) }}
                            </p>

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top small text-muted">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-1 d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size: 0.75rem;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <span class="fw-semibold text-dark">{{ $topic->user->name }}</span>
                                </div>
                                <div class="d-flex gap-3">
                                    <span><i class="bi bi-eye"></i> {{ $topic->views_count }} views</span>
                                    <span class="text-success fw-bold"><i class="bi bi-chat-dots-fill text-success"></i> {{ $topic->replies->count() }} replies</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card card-custom p-5 text-center text-muted">
                            <i class="bi bi-chat-square-dots fs-1 mb-2 text-secondary"></i>
                            <h5 class="fw-bold">No discussion topics found</h5>
                            <p class="small mb-3">Have a question about Elahera? Start the first discussion!</p>
                            <div>
                                <a href="{{ route('forum.create') }}" class="btn btn-primary-custom btn-sm px-4">Post a Question</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $topics->links('pagination::bootstrap-5') }}
                </div>

            </div>


            <!-- Right (1/3 Width): Categories & Guidelines -->
            <div class="col-lg-4">
                
                <!-- Category Filter Box -->
                <div class="card card-custom border-0 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="bi bi-tags-fill text-success me-2"></i> Forum Channels
                    </h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <a href="{{ route('forum.index') }}" class="text-decoration-none d-flex justify-content-between align-items-center {{ !$categorySlug ? 'fw-bold text-success' : 'text-secondary' }}">
                                <span>All Topics</span>
                                <span class="badge bg-light text-dark border">{{ \App\Models\ForumTopic::count() }}</span>
                            </a>
                        </li>
                        @foreach($categories as $fc)
                            <li class="mb-2">
                                <a href="{{ route('forum.index', ['category' => $fc->slug]) }}" class="text-decoration-none d-flex justify-content-between align-items-center {{ $categorySlug === $fc->slug ? 'fw-bold text-success' : 'text-secondary' }}">
                                    <span>{{ $fc->getLocalizedName() }}</span>
                                    <span class="badge bg-light text-dark border">{{ $fc->topics_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Direct Publication Notice -->
                <div class="card card-custom border-0 shadow-sm p-4 bg-light">
                    <h6 class="fw-bold text-dark mb-2">
                        <i class="bi bi-check2-circle text-success me-1"></i> Instant Publication
                    </h6>
                    <p class="small text-muted mb-3" style="font-size: 0.82rem; line-height: 1.5;">
                        Questions and replies are published directly to support real-time assistance for travelers. Please be respectful, helpful, and maintain community etiquette.
                    </p>
                    <a href="{{ route('forum.create') }}" class="btn btn-outline-success btn-sm w-100 fw-bold">
                        Ask a Question
                    </a>
                </div>

            </div>

        </div>

    </div>

@endsection

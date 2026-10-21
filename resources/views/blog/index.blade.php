@extends('layouts.app')

@section('title', 'Community Travel Blog - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        
        <!-- Header & Intro -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <span class="badge bg-info text-white px-3 py-1.5 rounded-pill mb-2">Community Stories</span>
                <h1 class="display-6 fw-bold text-dark mb-1">Traveler Experiences & Guides</h1>
                <p class="text-secondary small mb-0">Insights, hidden spots, and local journeys shared by registered community members.</p>
            </div>
            <div>
                <a href="{{ route('blog.create') }}" class="btn btn-primary-custom fw-bold shadow-sm px-4">
                    <i class="bi bi-pencil-square me-1"></i> Write a Blog Post
                </a>
            </div>
        </div>

        <div class="row g-4">
            
            <!-- Left (2/3 Width): Blog Posts Listing & Sorting Controls -->
            <div class="col-lg-8">
                
                <!-- Sort Bar -->
                <div class="d-flex flex-wrap justify-content-between align-items-center bg-white p-3 rounded-3 border mb-4 shadow-sm">
                    <div class="small fw-bold text-secondary mb-2 mb-sm-0">
                        Showing {{ $posts->total() }} published articles
                        @if($categorySlug)
                            in <span class="badge bg-success">{{ $categorySlug }}</span>
                        @endif
                    </div>
                    <!-- Sort Pills -->
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('blog.index', array_merge(request()->query(), ['sort' => 'latest'])) }}" class="btn {{ $sort === 'latest' ? 'btn-success text-white' : 'btn-outline-secondary' }}">
                            <i class="bi bi-clock me-1"></i> Latest
                        </a>
                        <a href="{{ route('blog.index', array_merge(request()->query(), ['sort' => 'views'])) }}" class="btn {{ $sort === 'views' ? 'btn-success text-white' : 'btn-outline-secondary' }}">
                            <i class="bi bi-eye me-1"></i> Most Viewed
                        </a>
                        <a href="{{ route('blog.index', array_merge(request()->query(), ['sort' => 'comments'])) }}" class="btn {{ $sort === 'comments' ? 'btn-success text-white' : 'btn-outline-secondary' }}">
                            <i class="bi bi-chat-text me-1"></i> Most Discussed
                        </a>
                    </div>
                </div>

                <!-- Articles Feed -->
                <div class="d-flex flex-column gap-4 mb-5">
                    @forelse($posts as $post)
                        @php
                            $pImg = $post->featured_image ? (file_exists(public_path($post->featured_image)) ? asset($post->featured_image) : asset('images/blog/blog1.svg')) : asset('images/blog/blog1.svg');
                        @endphp
                        <div class="card card-custom border-0 shadow-sm overflow-hidden">
                            <div class="row g-0 align-items-center">
                                <div class="col-md-5">
                                    <a href="{{ route('blog.show', $post->slug) }}">
                                        <img src="{{ $pImg }}" class="w-100 h-100 object-fit-cover" style="min-height: 220px; max-height: 240px;" alt="{{ $post->title }}">
                                    </a>
                                </div>
                                <div class="col-md-7">
                                    <div class="card-body p-4 d-flex flex-column h-100">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-light text-secondary border small">{{ $post->category?->name_en ?: 'Travel' }}</span>
                                            <span class="small text-muted" style="font-size: 0.75rem;">
                                                <i class="bi bi-calendar3 me-1"></i> {{ $post->created_at->format('M d, Y') }}
                                            </span>
                                        </div>

                                        <h5 class="fw-bold mb-2">
                                            <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none hover-success">
                                                {{ $post->title }}
                                            </a>
                                        </h5>

                                        <p class="text-secondary small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ Str::limit(strip_tags($post->content), 120) }}
                                        </p>

                                        <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top text-muted small">
                                            <span class="fw-semibold text-dark">
                                                <i class="bi bi-person-circle text-success me-1"></i> {{ $post->user->name }}
                                            </span>
                                            <div class="d-flex gap-3">
                                                <span><i class="bi bi-eye text-primary"></i> {{ $post->views_count }}</span>
                                                <span><i class="bi bi-chat-text text-warning"></i> {{ $post->comments->count() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card card-custom p-5 text-center text-muted">
                            <i class="bi bi-journal-x fs-1 mb-2 text-secondary"></i>
                            <h5 class="fw-bold">No published articles yet</h5>
                            <p class="small mb-3">Be the first community member to write a travel story!</p>
                            <div>
                                <a href="{{ route('blog.create') }}" class="btn btn-success btn-sm px-3">Write Article</a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $posts->links('pagination::bootstrap-5') }}
                </div>

            </div>


            <!-- Right (1/3 Width): Sidebar Widgets (Categories, Top Contributors, Guidelines) -->
            <div class="col-lg-4">
                
                <!-- Categories Widget -->
                <div class="card card-custom border-0 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="bi bi-folder2-open text-success me-2"></i> Categories
                    </h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <a href="{{ route('blog.index') }}" class="text-decoration-none d-flex justify-content-between align-items-center {{ !$categorySlug ? 'fw-bold text-success' : 'text-secondary' }}">
                                <span>All Articles</span>
                                <span class="badge bg-light text-dark border">{{ \App\Models\BlogPost::where('is_approved', true)->count() }}</span>
                            </a>
                        </li>
                        @foreach($categories as $cat)
                            <li class="mb-2">
                                <a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="text-decoration-none d-flex justify-content-between align-items-center {{ $categorySlug === $cat->slug ? 'fw-bold text-success' : 'text-secondary' }}">
                                    <span>{{ $cat->getLocalizedName() }}</span>
                                    <span class="badge bg-light text-dark border">{{ $cat->posts_count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Top Contributors Widget -->
                <div class="card card-custom border-0 shadow-sm p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                        <i class="bi bi-trophy-fill text-warning me-2"></i> Top Contributors
                    </h5>
                    <div class="d-flex flex-column gap-3">
                        @forelse($contributors as $con)
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-success bg-opacity-10 text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold small text-dark">{{ $con->name }}</div>
                                        <div class="small text-muted" style="font-size: 0.72rem;">Community Guide</div>
                                    </div>
                                </div>
                                <span class="badge bg-light text-secondary border small">{{ $con->blog_posts_count }} posts</span>
                            </div>
                        @empty
                            <p class="small text-muted mb-0">Join and start contributing to earn a spot on the leaderboard!</p>
                        @endforelse
                    </div>
                </div>

                <!-- Contributor Notice -->
                <div class="card card-custom border-0 shadow-sm p-4 bg-light">
                    <h6 class="fw-bold text-dark mb-2"><i class="bi bi-shield-check text-success me-1"></i> Contributor Guidelines</h6>
                    <p class="small text-muted mb-3" style="font-size: 0.8rem; line-height: 1.5;">
                        Community articles help fellow travelers explore safely and authentically. Posts submitted by community members are reviewed by administrators before going live.
                    </p>
                    <a href="{{ route('blog.create') }}" class="btn btn-outline-success btn-sm fw-bold w-100">
                        Share Your Travel Story
                    </a>
                </div>

            </div>

        </div>

    </div>

@endsection

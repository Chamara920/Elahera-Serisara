@extends('layouts.app')

@section('title', 'My Blog Posts - ' . __('messages.brand_name'))

@section('content')

    <div class="container-xl py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill mb-2 fw-bold">My Contributions</span>
                <h1 class="display-6 fw-bold text-dark mb-1">My Community Blog Posts</h1>
                <p class="text-secondary small mb-0">Track your published stories and pending article submissions.</p>
            </div>
            <div>
                <a href="{{ route('blog.create') }}" class="btn btn-primary-custom fw-bold shadow-sm px-4">
                    <i class="bi bi-pencil-square me-1"></i> Write New Article
                </a>
            </div>
        </div>

        <div class="card card-custom border-0 shadow-sm p-4 p-md-5">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr>
                                <td class="fw-bold text-dark">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="text-dark text-decoration-none hover-success">
                                        {{ $post->title }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border">{{ $post->category?->name_en ?: 'General' }}</span>
                                </td>
                                <td>
                                    @if($post->is_approved)
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Published</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i> Pending Approval</span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    <i class="bi bi-eye"></i> {{ $post->views_count }}
                                </td>
                                <td class="small text-muted">
                                    {{ $post->created_at->format('M d, Y') }}
                                </td>
                                <td>
                                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-success btn-sm">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    You have not submitted any blog posts yet. Click "Write New Article" to get started!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $posts->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>

@endsection

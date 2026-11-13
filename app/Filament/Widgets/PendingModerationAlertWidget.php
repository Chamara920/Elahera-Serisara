<?php

namespace App\Filament\Widgets;

use App\Models\BlogPost;
use App\Models\DestinationReview;
use App\Models\NewsEvent;
use Filament\Widgets\Widget;

class PendingModerationAlertWidget extends Widget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected string $view = 'filament.widgets.pending-moderation-alert';

    public function getViewData(): array
    {
        $pendingReviews = DestinationReview::with(['destination', 'user'])
            ->where('is_approved', false)
            ->latest()
            ->get();

        $pendingBlogs = BlogPost::with(['user', 'category'])
            ->where('is_approved', false)
            ->latest()
            ->get();

        $pendingNews = NewsEvent::with(['user', 'category'])
            ->where('is_approved', false)
            ->latest()
            ->get();

        $totalPending = $pendingReviews->count() + $pendingBlogs->count() + $pendingNews->count();

        return [
            'pendingReviews' => $pendingReviews,
            'pendingBlogs' => $pendingBlogs,
            'pendingNews' => $pendingNews,
            'totalPending' => $totalPending,
        ];
    }
}

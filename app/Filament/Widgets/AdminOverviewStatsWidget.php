<?php

namespace App\Filament\Widgets;

use App\Models\BlogPost;
use App\Models\BusStop;
use App\Models\Destination;
use App\Models\DestinationReview;
use App\Models\ForumReply;
use App\Models\ForumTopic;
use App\Models\NewsEvent;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminOverviewStatsWidget extends BaseWidget
{
    protected static bool $isLazy = false;

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $destCount = Destination::count();
        $busStopsCount = BusStop::count();
        $hubsCount = BusStop::where('is_hub', true)->count();
        $pendingBlogs = BlogPost::where('is_approved', false)->count();
        $totalBlogs = BlogPost::count();
        $pendingNews = NewsEvent::where('is_approved', false)->count();
        $totalNews = NewsEvent::count();
        $totalTopics = ForumTopic::count();
        $totalReplies = ForumReply::count();
        $pendingReviews = DestinationReview::where('is_approved', false)->count();
        $totalReviews = DestinationReview::count();
        $avgRating = round(DestinationReview::avg('rating') ?: 5.0, 1);
        $totalUsers = User::count();

        return [
            Stat::make('Tourist Destinations', $destCount)
                ->description('Active Attractions in Elahera Corridor')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('success')
                ->chart([2, 4, 6, 5, 8, 9, max(1, $destCount)])
                ->url(route('filament.admin.resources.destinations.index')),

            Stat::make('Bus Stops & Halts', $busStopsCount)
                ->description("{$hubsCount} Transfer Hubs (බස් මාරුවීම් ස්ථාන)")
                ->descriptionIcon('heroicon-m-arrows-right-left')
                ->color('info')
                ->chart([3, 5, 4, 6, 7, 8, max(1, $busStopsCount)])
                ->url(route('filament.admin.resources.bus-stops.index')),

            Stat::make('Destination Reviews & Comments', $totalReviews)
                ->description($pendingReviews > 0 ? "⚠️ {$pendingReviews} review(s) pending approval" : "Avg Rating: {$avgRating} / 5.0 ★")
                ->descriptionIcon($pendingReviews > 0 ? 'heroicon-m-clock' : 'heroicon-m-star')
                ->color($pendingReviews > 0 ? 'warning' : 'amber')
                ->chart([5, 4, 6, 5, 7, 8, max(1, $totalReviews)])
                ->url(route('filament.admin.resources.destination-reviews.index')),

            Stat::make('Community Blog Articles', $totalBlogs)
                ->description($pendingBlogs > 0 ? "⚠️ {$pendingBlogs} post(s) pending review" : "{$totalBlogs} published stories")
                ->descriptionIcon($pendingBlogs > 0 ? 'heroicon-m-clock' : 'heroicon-m-document-text')
                ->color($pendingBlogs > 0 ? 'warning' : 'info')
                ->chart([1, 3, 2, 4, 3, 5, max(1, $totalBlogs)])
                ->url(route('filament.admin.resources.blog-posts.index')),

            Stat::make('News Notices & Events', $totalNews)
                ->description($pendingNews > 0 ? "⚠️ {$pendingNews} submission(s) pending" : "{$totalNews} public notices")
                ->descriptionIcon($pendingNews > 0 ? 'heroicon-m-clock' : 'heroicon-m-newspaper')
                ->color($pendingNews > 0 ? 'warning' : 'primary')
                ->chart([2, 3, 4, 3, 5, 6, max(1, $totalNews)])
                ->url(route('filament.admin.resources.news-events.index')),

            Stat::make('Forum Community Topics', $totalTopics)
                ->description("{$totalReplies} traveler replies")
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('info')
                ->chart([3, 2, 4, 5, 6, 7, max(1, $totalTopics)])
                ->url(route('filament.admin.resources.forum-topics.index')),

            Stat::make('Registered Members', $totalUsers)
                ->description('Local Guides & Community Travelers')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([1, 2, 3, 4, 5, 6, max(1, $totalUsers)])
                ->url(route('filament.admin.resources.users.index')),
        ];
    }
}

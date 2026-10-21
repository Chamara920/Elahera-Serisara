<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\Destination;
use App\Models\DestinationReview;
use App\Models\User;
use Tests\TestCase;

class ReviewCommentModerationTest extends TestCase
{
    /**
     * Test admin can delete destination reviews and ratings.
     */
    public function test_admin_can_delete_destination_review(): void
    {
        $admin = User::where('role', 'admin')->first();
        $member = User::where('role', 'community_member')->first();
        $destination = Destination::first();

        $review = DestinationReview::create([
            'destination_id' => $destination->id,
            'user_id' => $member->id,
            'rating' => 5,
            'comment' => 'Spectacular views and hydraulic engineering!',
            'is_approved' => true,
        ]);

        $this->assertDatabaseHas('destination_reviews', ['id' => $review->id]);

        $response = $this->actingAs($admin)->delete(route('destinations.reviews.destroy', $review->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('destination_reviews', ['id' => $review->id]);
    }

    /**
     * Test non-admin cannot delete reviews.
     */
    public function test_regular_user_cannot_delete_destination_review(): void
    {
        $member = User::where('role', 'community_member')->first();
        $destination = Destination::first();

        $review = DestinationReview::create([
            'destination_id' => $destination->id,
            'user_id' => $member->id,
            'rating' => 4,
            'comment' => 'Great place to visit with family.',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($member)->delete(route('destinations.reviews.destroy', $review->id));
        $response->assertStatus(403);

        $this->assertDatabaseHas('destination_reviews', ['id' => $review->id]);

        // Cleanup
        $review->delete();
    }

    /**
     * Test admin can delete blog comments.
     */
    public function test_admin_can_delete_blog_comment(): void
    {
        $admin = User::where('role', 'admin')->first();
        $member = User::where('role', 'community_member')->first();
        $post = BlogPost::first();

        $comment = BlogComment::create([
            'blog_post_id' => $post->id,
            'user_id' => $member->id,
            'comment' => 'Very informative post about Elahera.',
        ]);

        $this->assertDatabaseHas('blog_comments', ['id' => $comment->id]);

        $response = $this->actingAs($admin)->delete(route('blog.comments.destroy', $comment->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('blog_comments', ['id' => $comment->id]);
    }

    /**
     * Test admin can delete news/event comments.
     */
    public function test_admin_can_delete_news_event_comment(): void
    {
        $admin = User::where('role', 'admin')->first();
        $member = User::where('role', 'community_member')->first();
        $news = \App\Models\NewsEvent::first();

        $comment = \App\Models\NewsEventComment::create([
            'news_event_id' => $news->id,
            'user_id' => $member->id,
            'comment' => 'Great initiative for local tourism!',
        ]);

        $this->assertDatabaseHas('news_event_comments', ['id' => $comment->id]);

        $response = $this->actingAs($admin)->delete(route('news.comments.destroy', $comment->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('news_event_comments', ['id' => $comment->id]);
    }

    /**
     * Test regular user cannot delete news comments.
     */
    public function test_regular_user_cannot_delete_news_event_comment(): void
    {
        $member = User::where('role', 'community_member')->first();
        $news = \App\Models\NewsEvent::first();

        $comment = \App\Models\NewsEventComment::create([
            'news_event_id' => $news->id,
            'user_id' => $member->id,
            'comment' => 'Regular user comment.',
        ]);

        $response = $this->actingAs($member)->delete(route('news.comments.destroy', $comment->id));
        $response->assertStatus(403);

        $this->assertDatabaseHas('news_event_comments', ['id' => $comment->id]);
        $comment->delete();
    }

    /**
     * Test admin can access Reviews & Ratings in Filament Admin panel.
     */
    public function test_admin_can_access_filament_destination_reviews(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/destination-reviews');
        $response->assertStatus(200);
    }
}

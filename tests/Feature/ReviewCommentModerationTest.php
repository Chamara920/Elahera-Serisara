<?php

namespace Tests\Feature;

use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Models\Destination;
use App\Models\DestinationReview;
use App\Models\NewsEvent;
use App\Models\NewsEventComment;
use App\Models\User;
use Tests\TestCase;

class ReviewCommentModerationTest extends TestCase
{
    protected function getOrCreateTestDependencies(): array
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@elaheraserisara.lk'],
            ['name' => 'System Administrator', 'password' => bcrypt('password123'), 'role' => 'admin']
        );

        $member = User::firstOrCreate(
            ['email' => 'test_member@elaheraserisara.lk'],
            ['name' => 'Test Member', 'password' => bcrypt('password123'), 'role' => 'community_member']
        );

        $destination = Destination::firstOrCreate(
            ['slug' => 'test-destination-review'],
            [
                'title_en' => 'Test Destination Review',
                'short_description_en' => 'Short test description',
                'description_en' => 'Full test description',
                'category' => 'Waterfalls',
                'route_zone' => 'Zone A',
                'latitude' => 7.9,
                'longitude' => 80.8,
                'is_active' => true,
            ]
        );

        $post = BlogPost::firstOrCreate(
            ['slug' => 'test-blog-post-review'],
            [
                'user_id' => $member->id,
                'title' => 'Test Blog Post',
                'content' => 'Test blog content',
                'is_approved' => true,
            ]
        );

        $news = NewsEvent::firstOrCreate(
            ['slug' => 'test-news-event-review'],
            [
                'user_id' => $member->id,
                'title' => 'Test News Event',
                'type' => 'news',
                'content' => 'Test news content',
                'is_approved' => true,
            ]
        );

        return compact('admin', 'member', 'destination', 'post', 'news');
    }

    /**
     * Test admin can delete destination reviews and ratings.
     */
    public function test_admin_can_delete_destination_review(): void
    {
        $deps = $this->getOrCreateTestDependencies();

        $review = DestinationReview::create([
            'destination_id' => $deps['destination']->id,
            'user_id' => $deps['member']->id,
            'rating' => 5,
            'comment' => 'Spectacular views and hydraulic engineering!',
            'is_approved' => true,
        ]);

        $this->assertDatabaseHas('destination_reviews', ['id' => $review->id]);

        $response = $this->actingAs($deps['admin'])->delete(route('destinations.reviews.destroy', $review->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('destination_reviews', ['id' => $review->id]);
    }

    /**
     * Test non-admin cannot delete reviews.
     */
    public function test_regular_user_cannot_delete_destination_review(): void
    {
        $deps = $this->getOrCreateTestDependencies();

        $review = DestinationReview::create([
            'destination_id' => $deps['destination']->id,
            'user_id' => $deps['member']->id,
            'rating' => 4,
            'comment' => 'Great place to visit with family.',
            'is_approved' => true,
        ]);

        $response = $this->actingAs($deps['member'])->delete(route('destinations.reviews.destroy', $review->id));
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
        $deps = $this->getOrCreateTestDependencies();

        $comment = BlogComment::create([
            'blog_post_id' => $deps['post']->id,
            'user_id' => $deps['member']->id,
            'comment' => 'Very informative post about Elahera.',
        ]);

        $this->assertDatabaseHas('blog_comments', ['id' => $comment->id]);

        $response = $this->actingAs($deps['admin'])->delete(route('blog.comments.destroy', $comment->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('blog_comments', ['id' => $comment->id]);
    }

    /**
     * Test admin can delete news/event comments.
     */
    public function test_admin_can_delete_news_event_comment(): void
    {
        $deps = $this->getOrCreateTestDependencies();

        $comment = NewsEventComment::create([
            'news_event_id' => $deps['news']->id,
            'user_id' => $deps['member']->id,
            'comment' => 'Great initiative for local tourism!',
        ]);

        $this->assertDatabaseHas('news_event_comments', ['id' => $comment->id]);

        $response = $this->actingAs($deps['admin'])->delete(route('news.comments.destroy', $comment->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('news_event_comments', ['id' => $comment->id]);
    }

    /**
     * Test regular user cannot delete news comments.
     */
    public function test_regular_user_cannot_delete_news_event_comment(): void
    {
        $deps = $this->getOrCreateTestDependencies();

        $comment = NewsEventComment::create([
            'news_event_id' => $deps['news']->id,
            'user_id' => $deps['member']->id,
            'comment' => 'Regular user comment.',
        ]);

        $response = $this->actingAs($deps['member'])->delete(route('news.comments.destroy', $comment->id));
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

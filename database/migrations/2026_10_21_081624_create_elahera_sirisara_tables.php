<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Elahera Sirisara application.
     */
    public function up(): void
    {
        // Add roles and profile fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('community_member')->after('email'); // admin, community_member
            $table->string('avatar')->nullable()->after('password');
            $table->string('phone')->nullable()->after('avatar');
            $table->text('bio')->nullable()->after('phone');
        });

        // Destinations table
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('title_en'); // Mandatory in English
            $table->string('title_si')->nullable(); // Optional in Sinhala
            $table->string('slug')->unique();
            $table->text('short_description_en');
            $table->text('short_description_si')->nullable();
            $table->longText('description_en');
            $table->longText('description_si')->nullable();
            $table->string('category')->default('Nature & Wildlife'); // Waterfalls, Viewpoints, Nature & Wildlife, Historical & Cultural, Canals & Lakes
            $table->string('category_si')->nullable();
            $table->string('route_zone')->default('Zone A - Bakamuna Route'); // Route Zone
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();

            // Metrics & Travel data
            $table->decimal('distance_km', 8, 2)->default(0);
            $table->integer('travel_time_minutes')->default(0);
            $table->integer('stay_duration_minutes')->default(60);

            // Guide & Safety details
            $table->text('safety_alerts_en')->nullable();
            $table->text('safety_alerts_si')->nullable();
            $table->text('packing_list_en')->nullable();
            $table->text('packing_list_si')->nullable();
            $table->text('dress_code_en')->nullable();
            $table->text('dress_code_si')->nullable();

            // Transport integration
            $table->string('nearest_bus_stop')->nullable();
            $table->string('bus_route_numbers')->nullable();

            // Coordinates
            $table->decimal('latitude', 10, 7)->default(7.9866);
            $table->decimal('longitude', 10, 7)->default(80.8144);

            // Flags
            $table->boolean('is_featured')->default(false);
            $table->integer('featured_priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
        });

        // Destination Reviews & Ratings (for community users)
        Schema::create('destination_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('rating')->default(5); // 1 to 5
            $table->text('comment');
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
        });

        // Hero Sliders (Filament admin controlled)
        Schema::create('hero_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title_en');
            $table->string('title_si')->nullable();
            $table->string('subtitle_en')->nullable();
            $table->string('subtitle_si')->nullable();
            $table->string('image')->nullable();
            $table->string('button1_text_en')->default('Smart Trip Planner');
            $table->string('button1_text_si')->default('ස්මාර්ට් චාරිකා සැලසුම්කරු');
            $table->string('button1_url')->default('/trip-planner');
            $table->string('button2_text_en')->default('Explore Destinations');
            $table->string('button2_text_si')->default('ගමනාන්ත ගවේෂණය');
            $table->string('button2_url')->default('/destinations');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Bus Routes
        Schema::create('bus_routes', function (Blueprint $table) {
            $table->id();
            $table->string('route_number'); // e.g. 422, 45/1
            $table->string('origin'); // e.g. Matale / Polonnaruwa
            $table->string('destination'); // e.g. Bakamuna / Habarana
            $table->string('service_type')->default('Normal'); // Normal, Semi-Luxury, Express
            $table->string('origin_departure_time'); // e.g. 05:30 AM
            $table->string('via_cities')->nullable(); // e.g. Via Naula, Elahera
            $table->text('remarks')->nullable(); // Special dates, delay notice
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Bus Route Halts (Repeatable schedule)
        Schema::create('bus_route_halts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_route_id')->constrained('bus_routes')->cascadeOnDelete();
            $table->string('stop_name'); // e.g. Elahera Junction, Bakamuna Town
            $table->string('arrival_time')->nullable(); // e.g. 06:15 AM
            $table->string('departure_time')->nullable(); // e.g. 06:20 AM
            $table->foreignId('destination_id')->nullable()->constrained('destinations')->nullOnDelete();
            $table->string('nearest_spot_name')->nullable();
            $table->integer('stop_order')->default(0);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });

        // Saved Trips (for registered community members)
        Schema::create('saved_trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->default('My Elahera Day Trip');
            $table->date('trip_date')->nullable();
            $table->string('start_location')->default('Bakamuna');
            $table->time('start_time')->default('08:00:00');
            $table->time('end_time')->default('18:00:00');
            $table->string('transport_mode')->default('Car/Van');
            $table->boolean('meal_break')->default(true);
            $table->json('stops_data'); // Destination IDs, arrival/departure, stay duration
            $table->integer('total_duration_minutes')->default(0);
            $table->text('google_maps_url')->nullable();
            $table->timestamps();
        });

        // Blog Categories
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_si')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Blog Posts (Community member submission - requires Admin approval)
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('blog_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->boolean('is_approved')->default(false); // Requires admin approval
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
        });

        // Blog Comments (Registered users)
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('comment');
            $table->timestamps();
        });

        // Forum Categories
        Schema::create('forum_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_si')->nullable();
            $table->string('slug')->unique();
            $table->string('icon')->default('chat-bubble-left-right');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Forum Topics (Direct publication - no approval needed)
        Schema::create('forum_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_category_id')->constrained('forum_categories')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
        });

        // Forum Replies
        Schema::create('forum_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_topic_id')->constrained('forum_topics')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->longText('content');
            $table->timestamps();
        });

        // News & Events (Community submission - requires Admin approval)
        Schema::create('news_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('type')->default('news'); // 'news' or 'event'
            $table->date('event_date')->nullable();
            $table->string('location')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->boolean('is_approved')->default(false); // Requires admin approval
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
        });

        // News & Events Comments
        Schema::create('news_event_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_event_id')->constrained('news_events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('comment');
            $table->timestamps();
        });

        // Contact Messages
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // Emergency Contacts
        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('institution_name_en');
            $table->string('institution_name_si')->nullable();
            $table->string('category'); // Police, Hospital, Wildlife/Forest, Disaster, Local Council
            $table->string('phone_primary');
            $table->string('phone_secondary')->nullable();
            $table->string('location')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_contacts');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('news_event_comments');
        Schema::dropIfExists('news_events');
        Schema::dropIfExists('forum_replies');
        Schema::dropIfExists('forum_topics');
        Schema::dropIfExists('forum_categories');
        Schema::dropIfExists('blog_comments');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('saved_trips');
        Schema::dropIfExists('bus_route_halts');
        Schema::dropIfExists('bus_routes');
        Schema::dropIfExists('hero_sliders');
        Schema::dropIfExists('destination_reviews');
        Schema::dropIfExists('destinations');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'avatar', 'phone', 'bio']);
        });
    }
};

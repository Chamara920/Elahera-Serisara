<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('news_categories')) {
            Schema::create('news_categories', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->id();
                $table->string('name_en');
                $table->string('name_si')->nullable();
                $table->string('slug', 191)->unique();
                $table->string('icon')->nullable();
                $table->text('description')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        Schema::table('news_events', function (Blueprint $table) {
            if (! Schema::hasColumn('news_events', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('user_id')->constrained('news_categories')->nullOnDelete();
            }
            if (! Schema::hasColumn('news_events', 'language')) {
                $table->string('language')->default('all')->after('type');
            }
            if (! Schema::hasColumn('news_events', 'title_si')) {
                $table->string('title_si')->nullable()->after('title');
            }
            if (! Schema::hasColumn('news_events', 'content_si')) {
                $table->longText('content_si')->nullable()->after('content');
            }
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            if (! Schema::hasColumn('blog_posts', 'language')) {
                $table->string('language')->default('all')->after('category_id');
            }
            if (! Schema::hasColumn('blog_posts', 'title_si')) {
                $table->string('title_si')->nullable()->after('title');
            }
            if (! Schema::hasColumn('blog_posts', 'content_si')) {
                $table->longText('content_si')->nullable()->after('content');
            }
        });

        Schema::table('forum_topics', function (Blueprint $table) {
            if (! Schema::hasColumn('forum_topics', 'language')) {
                $table->string('language')->default('all')->after('forum_category_id');
            }
            if (! Schema::hasColumn('forum_topics', 'title_si')) {
                $table->string('title_si')->nullable()->after('title');
            }
            if (! Schema::hasColumn('forum_topics', 'content_si')) {
                $table->longText('content_si')->nullable()->after('content');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forum_topics', function (Blueprint $table) {
            $table->dropColumn(['language', 'title_si', 'content_si']);
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn(['language', 'title_si', 'content_si']);
        });

        Schema::table('news_events', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'language', 'title_si', 'content_si']);
        });

        Schema::dropIfExists('news_categories');
    }
};

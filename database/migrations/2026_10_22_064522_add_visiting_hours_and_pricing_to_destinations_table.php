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
        Schema::table('destinations', function (Blueprint $table) {
            $table->string('opening_time')->nullable()->default('06:00')->after('stay_duration_minutes');
            $table->string('closing_time')->nullable()->default('18:00')->after('opening_time');
            $table->boolean('is_always_open')->default(false)->after('closing_time');
            $table->json('closed_days')->nullable()->after('is_always_open');
            $table->string('closure_notes')->nullable()->after('closed_days');
            $table->json('best_months')->nullable()->after('closure_notes');
            $table->json('moderate_months')->nullable()->after('best_months');
            $table->json('not_recommended_months')->nullable()->after('moderate_months');
            $table->decimal('ticket_price_min', 10, 2)->default(0)->after('not_recommended_months');
            $table->decimal('ticket_price_max', 10, 2)->default(0)->after('ticket_price_min');
            $table->string('ticket_price_range')->nullable()->after('ticket_price_max');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('destinations', function (Blueprint $table) {
            $table->dropColumn([
                'opening_time',
                'closing_time',
                'is_always_open',
                'closed_days',
                'closure_notes',
                'best_months',
                'moderate_months',
                'not_recommended_months',
                'ticket_price_min',
                'ticket_price_max',
                'ticket_price_range',
            ]);
        });
    }
};

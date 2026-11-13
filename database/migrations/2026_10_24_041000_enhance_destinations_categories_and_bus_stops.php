<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to support multiple categories on destinations and connecting routes on bus stops.
     */
    public function up(): void
    {
        // 1. Add connecting_routes to bus_stops if not exists
        Schema::table('bus_stops', function (Blueprint $table) {
            if (! Schema::hasColumn('bus_stops', 'connecting_routes')) {
                $table->string('connecting_routes')->nullable()->after('code');
            }
        });

        // 2. Create destination_destination_category pivot table
        if (! Schema::hasTable('destination_destination_category')) {
            Schema::create('destination_destination_category', function (Blueprint $table) {
                $table->id();
                $table->foreignId('destination_id')->constrained('destinations')->cascadeOnDelete();
                $table->foreignId('destination_category_id')->constrained('destination_categories')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['destination_id', 'destination_category_id'], 'dest_cat_unique');
            });
        }

        // 3. Migrate existing destination categories into the pivot table
        $destinations = DB::table('destinations')->whereNotNull('category_id')->get(['id', 'category_id']);
        foreach ($destinations as $dest) {
            DB::table('destination_destination_category')->insertOrIgnore([
                'destination_id' => $dest->id,
                'destination_category_id' => $dest->category_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Populate connecting_routes for key known bus stops
        $stopRoutes = [
            'BAKAMUNA' => 'Route 422, Route 45/1, Route 582',
            'POLATHUWADIYA' => 'Route 422, Route 582',
            'ELAHERA_JNC' => 'Route 422, Route 45/1',
            'ELAHERA_ANICUT' => 'Route 422',
            'MORAGAHAKANDA' => 'Route 422',
            'NAULA' => 'A9 / Route 422, Route 45/1, Route 582',
            'MATALE' => 'Route 422, Route 45/1',
            'DAMBULLA' => 'Route 582, Route 422',
            'KADURUWELA' => 'Route 422, Route 45/1',
        ];

        foreach ($stopRoutes as $code => $routes) {
            DB::table('bus_stops')->where('code', $code)->whereNull('connecting_routes')->update([
                'connecting_routes' => $routes,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destination_destination_category');

        Schema::table('bus_stops', function (Blueprint $table) {
            if (Schema::hasColumn('bus_stops', 'connecting_routes')) {
                $table->dropColumn('connecting_routes');
            }
        });
    }
};

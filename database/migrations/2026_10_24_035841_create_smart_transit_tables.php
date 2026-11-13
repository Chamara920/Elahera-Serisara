<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Smart Public Transit Routing.
     */
    public function up(): void
    {
        // 1. Bus Stops Master Table (with coordinates & Hub flags)
        if (! Schema::hasTable('bus_stops')) {
            Schema::create('bus_stops', function (Blueprint $table) {
                $table->id();
                $table->string('name_en');
                $table->string('name_si')->nullable();
                $table->string('code')->unique(); // e.g. BKM_TOWN, PLW_JUNC, MRG_DAM, KMR_ELLA, DMB_TOWN
                $table->decimal('latitude', 10, 7)->default(7.9300);
                $table->decimal('longitude', 10, 7)->default(80.8200);
                $table->boolean('is_hub')->default(false); // Can be used as transfer junction (Bakamuna, Polathuwadiya, Elahera, Dambulla)
                $table->string('location_description_en')->nullable();
                $table->string('location_description_si')->nullable();
                $table->timestamps();
            });
        }

        // 2. Bus Trips (Daily turns / schedules for each route)
        if (! Schema::hasTable('bus_trips')) {
            Schema::create('bus_trips', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bus_route_id')->constrained('bus_routes')->cascadeOnDelete();
                $table->string('trip_code')->unique(); // e.g. 422_OUT_0600
                $table->string('trip_name_en'); // e.g. Kaduruwela to Matale - Early Morning
                $table->string('trip_name_si')->nullable(); // e.g. කඳුරුවෙල සිට මාතලේ - උදෑසන
                $table->enum('direction', ['outbound', 'inbound'])->default('outbound');
                $table->string('service_days')->default('all'); // all, weekdays, weekends, poya
                $table->string('origin_departure_time'); // e.g. 06:00 AM
                $table->integer('origin_departure_minutes'); // e.g. 360 (minutes past midnight)
                $table->string('destination_arrival_time')->nullable(); // e.g. 09:30 AM
                $table->integer('destination_arrival_minutes')->nullable(); // e.g. 570
                $table->boolean('is_active')->default(true);
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 3. Bus Stop Times (GTFS-like timetable schedule for each trip)
        if (! Schema::hasTable('bus_stop_times')) {
            Schema::create('bus_stop_times', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bus_trip_id')->constrained('bus_trips')->cascadeOnDelete();
                $table->foreignId('bus_stop_id')->constrained('bus_stops')->cascadeOnDelete();
                $table->integer('stop_sequence')->default(1);
                $table->string('arrival_time'); // e.g. 06:40 AM
                $table->string('departure_time'); // e.g. 06:45 AM
                $table->integer('arrival_minutes'); // Minutes past midnight (e.g. 400)
                $table->integer('departure_minutes'); // Minutes past midnight (e.g. 405)
                $table->string('remarks')->nullable();
                $table->timestamps();

                $table->index(['bus_trip_id', 'stop_sequence']);
                $table->index(['bus_stop_id', 'departure_minutes']);
            });
        }

        // 4. Update Destinations with nearest_bus_stop_id & walking minutes
        Schema::table('destinations', function (Blueprint $table) {
            if (! Schema::hasColumn('destinations', 'nearest_bus_stop_id')) {
                $table->foreignId('nearest_bus_stop_id')->nullable()->after('nearest_bus_stop')->constrained('bus_stops')->nullOnDelete();
            }
            if (! Schema::hasColumn('destinations', 'bus_stop_walk_minutes')) {
                $table->integer('bus_stop_walk_minutes')->default(5)->after('nearest_bus_stop_id');
            }
        });

        // 5. Update Bus Route Halts with optional bus_stop_id
        Schema::table('bus_route_halts', function (Blueprint $table) {
            if (! Schema::hasColumn('bus_route_halts', 'bus_stop_id')) {
                $table->foreignId('bus_stop_id')->nullable()->after('bus_route_id')->constrained('bus_stops')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bus_route_halts', function (Blueprint $table) {
            $table->dropForeign(['bus_stop_id']);
            $table->dropColumn('bus_stop_id');
        });

        Schema::table('destinations', function (Blueprint $table) {
            $table->dropForeign(['nearest_bus_stop_id']);
            $table->dropColumn(['nearest_bus_stop_id', 'bus_stop_walk_minutes']);
        });

        Schema::dropIfExists('bus_stop_times');
        Schema::dropIfExists('bus_trips');
        Schema::dropIfExists('bus_stops');
    }
};

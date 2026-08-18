<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('client')->nullable()->after('location');
            $table->string('land_area')->nullable()->after('client');
            $table->string('construction_area')->nullable()->after('land_area');
            $table->string('video_url')->nullable()->after('cover_image');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['client', 'land_area', 'construction_area', 'video_url']);
        });
    }
};

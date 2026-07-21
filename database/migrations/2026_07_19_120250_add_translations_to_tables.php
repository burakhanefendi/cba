<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('excerpt_en')->nullable()->after('excerpt');
            $table->longText('content_en')->nullable()->after('content');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('description_en')->nullable()->after('description');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('description_en')->nullable()->after('description');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('bio_en')->nullable()->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'excerpt_en', 'content_en']);
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'description_en']);
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('name_en');
        });
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'description_en']);
        });
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'bio_en']);
        });
    }
};

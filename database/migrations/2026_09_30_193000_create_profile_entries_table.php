<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profile_entries', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // award | publication
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->string('image')->nullable();
            $table->string('pdf')->nullable();
            $table->string('external_url')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('profile_entry_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_entry_id')->constrained('profile_entries')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profile_entry_project');
        Schema::dropIfExists('profile_entries');
    }
};

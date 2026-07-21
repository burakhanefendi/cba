<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_id')->constrained('news')->cascadeOnDelete();
            $table->string('title');
            $table->string('url');
            $table->enum('type', ['link', 'project'])->default('link');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_links');
    }
};

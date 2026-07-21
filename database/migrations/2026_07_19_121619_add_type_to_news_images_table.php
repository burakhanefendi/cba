<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('news_images', function (Blueprint $table) {
            // 'slider' = ana görsel slider, 'gallery' = alt galeri
            $table->string('type')->default('gallery')->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('news_images', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};

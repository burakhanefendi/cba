<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->unsignedInteger('order')->default(0)->after('folder');
        });

        // Mevcut kayıtlara id sırasına göre order ver
        DB::statement('UPDATE media SET `order` = id');
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Mevcut media kayıtlarındaki klasörleri aktar
        $existing = DB::table('media')->whereNotNull('folder')->distinct()->pluck('folder');
        foreach ($existing as $name) {
            DB::table('media_folders')->insertOrIgnore(['name' => $name, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('media_folders');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_project', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('project_id');
            $table->primary(['category_id', 'project_id']);
            $table->foreign('category_id')->references('id')->on('categories')->cascadeOnDelete();
            $table->foreign('project_id')->references('id')->on('projects')->cascadeOnDelete();
        });

        // Migrate existing category_id data to pivot table
        DB::table('projects')
            ->whereNotNull('category_id')
            ->get(['id', 'category_id'])
            ->each(function ($project) {
                DB::table('category_project')->insertOrIgnore([
                    'category_id' => $project->category_id,
                    'project_id'  => $project->id,
                ]);
            });

        // Drop old category_id column
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::dropIfExists('category_project');
    }
};

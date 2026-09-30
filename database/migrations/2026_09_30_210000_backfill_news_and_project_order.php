<?php

use App\Models\News;
use App\Models\Project;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $news = News::orderByDesc('published_at')->orderByDesc('id')->get();
        foreach ($news as $i => $item) {
            $item->update(['order' => $i]);
        }

        $projects = Project::orderBy('order')->orderByDesc('id')->get();
        foreach ($projects as $i => $project) {
            $project->update(['order' => $i]);
        }
    }

    public function down(): void
    {
        //
    }
};

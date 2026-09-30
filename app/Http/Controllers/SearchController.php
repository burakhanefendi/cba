<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\ProfileEntry;
use App\Models\Project;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $projects     = collect();
        $news         = collect();
        $awards       = collect();
        $publications = collect();
        $team         = collect();

        if (mb_strlen($q) >= 2) {
            $like = '%' . addcslashes($q, '%_\\') . '%';

            $projects = Project::where('is_active', true)
                ->where(function ($query) use ($like) {
                    $query->where('title', 'like', $like)
                        ->orWhere('title_en', 'like', $like)
                        ->orWhere('subtitle', 'like', $like)
                        ->orWhere('subtitle_en', 'like', $like)
                        ->orWhere('description', 'like', $like)
                        ->orWhere('description_en', 'like', $like)
                        ->orWhere('meta_text', 'like', $like)
                        ->orWhere('meta_text_en', 'like', $like)
                        ->orWhere('location', 'like', $like);
                })
                ->orderBy('title')
                ->limit(24)
                ->get();

            $news = News::where('is_active', true)
                ->where(function ($query) use ($like) {
                    $query->where('title', 'like', $like)
                        ->orWhere('title_en', 'like', $like)
                        ->orWhere('excerpt', 'like', $like)
                        ->orWhere('excerpt_en', 'like', $like)
                        ->orWhere('content', 'like', $like)
                        ->orWhere('content_en', 'like', $like);
                })
                ->orderByDesc('published_at')
                ->limit(12)
                ->get();

            $awards = ProfileEntry::awards()->where('is_active', true)
                ->where(function ($query) use ($like) {
                    $query->where('title', 'like', $like)
                        ->orWhere('title_en', 'like', $like)
                        ->orWhere('subtitle', 'like', $like)
                        ->orWhere('subtitle_en', 'like', $like);
                })
                ->orderBy('title')
                ->limit(12)
                ->get();

            $publications = ProfileEntry::publications()->where('is_active', true)
                ->where(function ($query) use ($like) {
                    $query->where('title', 'like', $like)
                        ->orWhere('title_en', 'like', $like)
                        ->orWhere('subtitle', 'like', $like)
                        ->orWhere('subtitle_en', 'like', $like);
                })
                ->orderBy('title')
                ->limit(12)
                ->get();

            $team = TeamMember::where('is_active', true)
                ->where(function ($query) use ($like) {
                    $query->where('name', 'like', $like)
                        ->orWhere('title', 'like', $like)
                        ->orWhere('title_en', 'like', $like)
                        ->orWhere('bio', 'like', $like)
                        ->orWhere('bio_en', 'like', $like);
                })
                ->orderBy('name')
                ->limit(12)
                ->get();
        }

        $total = $projects->count() + $news->count() + $awards->count()
            + $publications->count() + $team->count();

        return view('search.index', compact(
            'q', 'total', 'projects', 'news', 'awards', 'publications', 'team'
        ));
    }
}

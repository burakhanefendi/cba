<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $query = Project::with('categories')
            ->where('is_active', true)
            ->orderBy('order')
            ->orderByDesc('id');

        if ($request->filled('category')) {
            $query->whereHas('categories', fn($q) => $q->where('slug', $request->category));
        }

        $projects = $query->paginate(12)->withQueryString();

        if ($request->expectsJson() || $request->ajax()) {
            $locale = app()->getLocale();
            return response()->json([
                'data' => $projects->map(fn($p) => [
                    'url'         => $locale === 'en' ? url('en/projects/' . $p->slug) : route('projects.show', $p->slug),
                    'cover_image' => $p->cover_image ? asset('storage/' . $p->cover_image) : null,
                    'title'       => $p->trans('title'),
                ]),
                'next_page_url' => $projects->nextPageUrl(),
            ]);
        }

        return view('projects.index', compact('projects', 'categories'));
    }

    public function show(string $slug)
    {
        $project = Project::with([
            'categories',
            'sliderImages',
            'galleryImages',
            'designers',
            'team',
        ])->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $prevProject = Project::where('is_active', true)
            ->where('order', '<', $project->order)
            ->orderByDesc('order')->orderByDesc('id')
            ->first();

        $nextProject = Project::where('is_active', true)
            ->where('order', '>', $project->order)
            ->orderBy('order')->orderBy('id')
            ->first();

        $categoryIds = $project->categories->pluck('id');

        $similarProjects = $categoryIds->isNotEmpty()
            ? Project::with('categories')
                ->where('is_active', true)
                ->where('id', '!=', $project->id)
                ->whereHas('categories', fn($q) => $q->whereIn('categories.id', $categoryIds))
                ->orderBy('order')
                ->take(8)
                ->get()
            : collect();

        return view('projects.show', compact('project', 'prevProject', 'nextProject', 'similarProjects'));
    }
}

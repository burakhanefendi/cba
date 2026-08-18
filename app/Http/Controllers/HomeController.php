<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Project;
use App\Models\Setting;
use App\Models\HeroSlide;

class HomeController extends Controller
{
    public function index()
    {
        $heroSlides = HeroSlide::where('is_active', true)->orderBy('order')->get();

        $featuredProjects = Project::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('order')
            ->take(8)
            ->get();

        $featuredNews = News::where('is_active', true)
            ->where('is_featured', true)
            ->orderByDesc('published_at')
            ->take(4)
            ->get();

        return view('home', compact('heroSlides', 'featuredProjects', 'featuredNews'));
    }
}

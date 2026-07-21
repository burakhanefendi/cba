<?php

namespace App\Http\Controllers;

use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::where('is_active', true)
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('news.index', compact('news'));
    }

    public function show(string $slug)
    {
        $item = News::where('slug', $slug)
            ->where('is_active', true)
            ->with(['sliderImages', 'galleryImages', 'links'])
            ->firstOrFail();

        return view('news.show', compact('item'));
    }
}

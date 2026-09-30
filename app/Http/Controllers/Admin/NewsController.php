<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsImage;
use App\Models\NewsLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderByDesc('published_at')->paginate(20);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'published_at' => 'nullable|date',
            'cover_image'  => 'nullable|string|max:500',
        ]);

        $data = $request->only(['title', 'title_en', 'excerpt', 'excerpt_en', 'content', 'content_en', 'published_at', 'order', 'is_active']);
        $data['slug']      = Str::slug($request->title) . '-' . time();
        $data['is_active']    = $request->boolean('is_active');
        $data['is_featured']  = $request->boolean('is_featured');
        $data['published_at'] = $request->filled('published_at') ? $request->published_at : now();

        $news = News::create($data);

        $this->syncCoverImages($news, $request);
        $this->syncLinks($news, $request);
        $this->syncGalleryImages($news, $request);

        return redirect()->route('admin.news.index')->with('success', 'Haber eklendi.');
    }

    public function edit(News $news)
    {
        $news->load(['links', 'sliderImages', 'galleryImages']);
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'published_at' => 'nullable|date',
            'cover_image'  => 'nullable|string|max:500',
        ]);

        $data = $request->only(['title', 'title_en', 'excerpt', 'excerpt_en', 'content', 'content_en', 'order']);
        $data['is_active']    = $request->boolean('is_active');
        $data['is_featured']  = $request->boolean('is_featured');
        $data['published_at'] = $request->filled('published_at') ? $request->published_at : now();

        $news->update($data);

        $this->syncCoverImages($news, $request);
        $this->syncLinks($news, $request);
        $this->syncGalleryImages($news, $request);

        return redirect()->route('admin.news.index')->with('success', 'Haber güncellendi.');
    }

    public function destroy(News $news)
    {
        if ($news->cover_image) Storage::disk('public')->delete($news->cover_image);
        foreach ($news->images as $img) Storage::disk('public')->delete($img->image);
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Haber silindi.');
    }

    private function syncCoverImages(News $news, Request $request): void
    {
        $news->sliderImages()->delete();

        $paths = array_values(array_filter($request->input('slider_paths', [])));
        foreach ($paths as $i => $path) {
            NewsImage::create([
                'news_id' => $news->id,
                'image'   => $path,
                'type'    => 'slider',
                'order'   => $i,
            ]);
        }

        $manualCover = $request->input('cover_image');
        $news->update([
            'cover_image' => $manualCover ?: ($paths[0] ?? null),
        ]);
    }

    private function syncLinks(News $news, Request $request): void
    {
        $news->links()->delete();

        $titles = $request->input('link_titles', []);
        $urls   = $request->input('link_urls', []);
        $types  = $request->input('link_types', []);

        foreach ($titles as $i => $title) {
            if (!empty($title) && !empty($urls[$i])) {
                NewsLink::create([
                    'news_id' => $news->id,
                    'title'   => $title,
                    'url'     => $urls[$i],
                    'type'    => $types[$i] ?? 'link',
                    'order'   => $i,
                ]);
            }
        }
    }

    private function syncGalleryImages(News $news, Request $request): void
    {
        // Tüm mevcut galeri görsellerini sıfırla
        $news->galleryImages()->delete();

        $paths = $request->input('gallery_paths', []);
        foreach ($paths as $i => $path) {
            NewsImage::create([
                'news_id' => $news->id,
                'image'   => $path,
                'type'    => 'gallery',
                'order'   => $i,
            ]);
        }
    }
}

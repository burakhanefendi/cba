<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title', 'title_en', 'slug', 'excerpt', 'excerpt_en',
        'content', 'content_en', 'cover_image', 'published_at',
        'is_active', 'is_featured', 'order',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_featured'  => 'boolean',
        'published_at' => 'date',
    ];

    public function images()
    {
        return $this->hasMany(NewsImage::class)->orderBy('order');
    }

    public function sliderImages()
    {
        return $this->hasMany(NewsImage::class)->where('type', 'slider')->orderBy('order');
    }

    public function galleryImages()
    {
        return $this->hasMany(NewsImage::class)->where('type', 'gallery')->orderBy('order');
    }

    public function links()
    {
        return $this->hasMany(NewsLink::class)->orderBy('order');
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->published_at ? $this->published_at->format('d.m.Y') : '';
    }
}

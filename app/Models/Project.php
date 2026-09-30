<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title', 'title_en', 'subtitle', 'subtitle_en',
        'slug', 'description', 'description_en',
        'location', 'client', 'land_area', 'construction_area',
        'meta_text', 'meta_text_en',
        'year', 'cover_image', 'video_url', 'order',
        'is_active', 'is_featured',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class)->orderBy('order');
    }

    public function category()
    {
        return $this->belongsToMany(Category::class)->orderBy('order');
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order');
    }

    public function sliderImages()
    {
        return $this->hasMany(ProjectImage::class)->where('type', 'slider')->orderBy('order');
    }

    public function galleryImages()
    {
        return $this->hasMany(ProjectImage::class)->where('type', 'gallery')->orderBy('order');
    }

    public function designers()
    {
        return $this->hasMany(ProjectDesigner::class)->orderBy('order');
    }

    public function team()
    {
        return $this->hasMany(ProjectTeam::class)->orderBy('order');
    }
}

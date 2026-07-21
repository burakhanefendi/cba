<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasTranslations;

    protected $fillable = [
        'category_id', 'title', 'title_en', 'slug', 'description', 'description_en',
        'location', 'year', 'cover_image', 'order',
        'is_active', 'is_featured',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderBy('order');
    }
}

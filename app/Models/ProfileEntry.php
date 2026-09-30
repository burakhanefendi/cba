<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class ProfileEntry extends Model
{
    use HasTranslations;

    protected $fillable = [
        'type', 'title', 'title_en', 'subtitle', 'subtitle_en',
        'image', 'pdf', 'external_url', 'order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'profile_entry_project')->orderBy('title');
    }

    public function scopeAwards($query)
    {
        return $query->where('type', 'award');
    }

    public function scopePublications($query)
    {
        return $query->where('type', 'publication');
    }
}

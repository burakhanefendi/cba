<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'name_en', 'slug', 'order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}

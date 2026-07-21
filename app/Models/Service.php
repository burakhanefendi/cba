<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasTranslations;

    protected $fillable = ['title', 'title_en', 'description', 'description_en', 'icon', 'order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}

<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name', 'title', 'title_en', 'bio', 'bio_en', 'photo',
        'email', 'linkedin', 'order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsLink extends Model
{
    protected $fillable = ['news_id', 'title', 'url', 'type', 'order'];
}

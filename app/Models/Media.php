<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = ['name', 'path', 'mime_type', 'size', 'width', 'height'];

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 1) . ' KB';
        return $bytes . ' B';
    }
}

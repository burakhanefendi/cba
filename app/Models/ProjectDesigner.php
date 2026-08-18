<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDesigner extends Model
{
    protected $fillable = ['project_id', 'name', 'order'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

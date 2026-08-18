<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTeam extends Model
{
    protected $table = 'project_team';

    protected $fillable = ['project_id', 'name', 'order'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

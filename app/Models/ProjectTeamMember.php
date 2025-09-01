<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTeamMember extends Model
{
    protected $table = 'project_user';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

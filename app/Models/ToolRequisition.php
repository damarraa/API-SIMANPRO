<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ToolRequisition extends Model
{
    protected $table = 'tool_requisitions';

    protected $fillable = [
        'tr_number',
        'project_id',
        'requested_by',
        'request_date',
        'status',
        'notes'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ToolRequisitionItem::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

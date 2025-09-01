<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolRequisitionItem extends Model
{
    protected $table = 'tool_requisition_items';

    protected $fillable = [
        'tool_requisition_id',
        'tool_id',
        'quantity_requested'
    ];

    public function toolRequisition(): BelongsTo
    {
        return $this->belongsTo(ToolRequisition::class);
    }

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }
}

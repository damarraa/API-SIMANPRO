<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialRequisitionItem extends Model
{
    protected $table = 'material_requisition_items';

    protected $fillable = [
        'material_requisition_id',
        'material_id',
        'quantity_requested',
        'quantity_issued'
    ];

    public function materialRequisition(): BelongsTo
    {
        return $this->belongsTo(MaterialRequisition::class);
    }

    public function material(): BelongsTo
    {
        return  $this->belongsTo(Material::class);
    }
}

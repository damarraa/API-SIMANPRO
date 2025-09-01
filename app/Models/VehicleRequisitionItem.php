<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleRequisitionItem extends Model
{
    protected $table = 'vehicle_requisition_items';

    protected $fillable = [
        'vehicle_requisition_id',
        'vehicle_id'
    ];

    public function vehicleRequisition(): BelongsTo
    {
        return $this->belongsTo(VehicleRequisition::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}

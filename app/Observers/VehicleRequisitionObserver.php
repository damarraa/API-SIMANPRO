<?php

namespace App\Observers;

use App\Models\VehicleAssignment;
use App\Models\VehicleRequisition;

class VehicleRequisitionObserver
{
    /**
     * Handle the VehicleRequisition "created" event.
     */
    public function created(VehicleRequisition $vehicleRequisition): void
    {
        //
    }

    /**
     * Handle the VehicleRequisition "updated" event.
     */
    public function updated(VehicleRequisition $vehicleRequisition): void
    {
        if ($vehicleRequisition->wasChanged('status') && $vehicleRequisition->status === 'Issued') {
            foreach ($vehicleRequisition->items as $item) {
                VehicleAssignment::create([
                    'vehicle_id' => $item->vehicle_id,
                    'project_id' => $vehicleRequisition->project_id,
                    'user_id' => $vehicleRequisition->requested_by,
                    'start_datetime' => now(),
                    'start_odometer' => 0,
                    'notes' => 'Penugasan otomatis dari Permintaan Kendaraan #' . $vehicleRequisition->vr_number,
                ]);
            }
        }
    }

    /**
     * Handle the VehicleRequisition "deleted" event.
     */
    public function deleted(VehicleRequisition $vehicleRequisition): void
    {
        //
    }

    /**
     * Handle the VehicleRequisition "restored" event.
     */
    public function restored(VehicleRequisition $vehicleRequisition): void
    {
        //
    }

    /**
     * Handle the VehicleRequisition "force deleted" event.
     */
    public function forceDeleted(VehicleRequisition $vehicleRequisition): void
    {
        //
    }
}

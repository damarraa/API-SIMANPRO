<?php

namespace App\Observers;

use App\Models\MaterialRequisition;
use App\Models\MaterialRequisitionItem;
use App\Models\StockMovement;

class MaterialRequisitionObserver
{
    /**
     * Handle the MaterialRequisition "created" event.
     */
    public function created(MaterialRequisition $materialRequisition): void
    {
        //
    }

    /**
     * Handle the MaterialRequisition "updated" event.
     */
    public function updated(MaterialRequisition $materialRequisition): void
    {
        // Cek jika status baru saja diubah ke 'Issued'
        if ($materialRequisition->wasChanged('status') && $materialRequisition->status === 'Issued') {
            $project = $materialRequisition->project;
            // Ambil gudang default dari proyek terkait
            $warehouseId = $materialRequisition->project->default_warehouse_id;

            if ($warehouseId) {
                foreach ($materialRequisition->items as $item) {
                    StockMovement::create([
                        'material_id' => $item->material_id,
                        'warehouse_id' => $warehouseId,
                        'project_id' => $project->id,
                        'quantity' => -$item->quantity_requested,
                        'type' => 'out',
                        'remarks' => 'Pengambilan barang untuk Proyek: ' . $materialRequisition->project->job_name,
                        'movable_id' => $item->id,
                        'movable_type' => MaterialRequisitionItem::class,
                        'user_id' => auth()->id(),
                    ]);
                }
            }
        }
    }

    /**
     * Handle the MaterialRequisition "deleted" event.
     */
    public function deleted(MaterialRequisition $materialRequisition): void
    {
        //
    }

    /**
     * Handle the MaterialRequisition "restored" event.
     */
    public function restored(MaterialRequisition $materialRequisition): void
    {
        //
    }

    /**
     * Handle the MaterialRequisition "force deleted" event.
     */
    public function forceDeleted(MaterialRequisition $materialRequisition): void
    {
        //
    }
}

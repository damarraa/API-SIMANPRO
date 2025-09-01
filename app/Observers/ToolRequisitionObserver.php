<?php

namespace App\Observers;

use App\Models\AssetAssignment;
use App\Models\ToolRequisition;

class ToolRequisitionObserver
{
    /**
     * Handle the ToolRequisition "created" event.
     */
    public function created(ToolRequisition $toolRequisition): void
    {
        //
    }

    /**
     * Handle the ToolRequisition "updated" event.
     */
    public function updated(ToolRequisition $toolRequisition): void
    {
        if ($toolRequisition->wasChanged('status') && $toolRequisition->status === 'Issued') {
            foreach ($toolRequisition->items as $item) {
                AssetAssignment::create([
                    'tool_id' => $item->tool_id,
                    'project_id' => $toolRequisition->project_id,
                    'assigned_by' => auth()->id(),
                    'assigned_date' => now(),
                    'notes' => 'Penugasan otomatis dari Permintaan Alat #' . $toolRequisition->tr_number,
                ]);
            }
        }
    }

    /**
     * Handle the ToolRequisition "deleted" event.
     */
    public function deleted(ToolRequisition $toolRequisition): void
    {
        //
    }

    /**
     * Handle the ToolRequisition "restored" event.
     */
    public function restored(ToolRequisition $toolRequisition): void
    {
        //
    }

    /**
     * Handle the ToolRequisition "force deleted" event.
     */
    public function forceDeleted(ToolRequisition $toolRequisition): void
    {
        //
    }
}

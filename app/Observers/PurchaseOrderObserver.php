<?php

namespace App\Observers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\StockMovement;

class PurchaseOrderObserver
{
    /**
     * Handle the PurchaseOrder "created" event.
     */
    public function created(PurchaseOrder $purchaseOrder): void
    {
        //
    }

    /**
     * Handle the PurchaseOrder "updated" event.
     */
    public function updated(PurchaseOrder $purchaseOrder): void
    {
        // Cek jika status baru saja diubah menjadi Completed.
        if ($purchaseOrder->wasChanged('status') && $purchaseOrder->status === 'Completed') {
            foreach ($purchaseOrder->items as $item) {
                StockMovement::create([
                    'material_id' => $item->material_id,
                    'warehouse_id' => $purchaseOrder->warehouse_id,
                    'quantity' => $item->quantity,
                    'type' => 'in',
                    'remarks' => 'Penerimaan barang dari PO #' . $purchaseOrder->po_number,
                    'movable_id' => $item->id,
                    'movable_type' => PurchaseOrderItem::class,
                    'user_id' => auth()->id(),
                ]);
            }
        }
    }

    /**
     * Handle the PurchaseOrder "deleted" event.
     */
    public function deleted(PurchaseOrder $purchaseOrder): void
    {
        //
    }

    /**
     * Handle the PurchaseOrder "restored" event.
     */
    public function restored(PurchaseOrder $purchaseOrder): void
    {
        //
    }

    /**
     * Handle the PurchaseOrder "force deleted" event.
     */
    public function forceDeleted(PurchaseOrder $purchaseOrder): void
    {
        //
    }
}

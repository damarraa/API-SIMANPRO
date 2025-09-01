<?php

namespace App\Observers;

use App\Models\InventoryStock;
use App\Models\StockMovement;
use App\Models\User;
use App\Models\WorkActivityLog;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class WorkActivityLogObserver
{
    /**
     * Original version.
     * Handle the WorkActivityLog "created" event.
     */
    // public function created(WorkActivityLog $workActivityLog): void
    // {
    //     // 1. Ambil data-data terkait
    //     $workItem = $workActivityLog->workItem;

    //     // Jika karena suatu hal item pekerjaan tidak ditemukan, hentikan.
    //     if (!$workItem) {
    //         Log::error('Observer GAGAL: WorkItem tidak ditemukan untuk WorkActivityLog ID: ' . $workActivityLog->id);
    //         return;
    //     }

    //     // 2. Tentukan gudang yang akan digunakan dari proyek induk
    //     $defaultWarehouseId = $workItem->project->default_warehouse_id;

    //     // Jika proyek belum memiliki gudang default, hentikan proses dan catat di log.
    //     if (is_null($defaultWarehouseId)) {
    //         Log::warning('PROSES STOK DIHENTIKAN: Proyek ID ' . $workItem->project_id . ' tidak memiliki gudang default.');
    //         return;
    //     }

    //     // Log untuk verifikasi gudang mana yang dipakai
    //     Log::info('Observer terpicu untuk Proyek: ' . $workItem->project->job_name . '. Stok akan dikurangi dari Gudang ID: ' . $defaultWarehouseId);

    //     // 3. Loop melalui semua material yang dibutuhkan untuk item pekerjaan ini
    //     foreach ($workItem->workItemMaterials as $reqMaterial) {

    //         // Hitung berapa banyak material yang digunakan untuk volume realisasi saat ini
    //         $quantityUsed = ($reqMaterial->quantity / $workItem->volume) * $workActivityLog->realized_volume;

    //         // 4. Periksa ketersediaan stok (Jaring Pengaman)
    //         $inventoryStock = InventoryStock::where('warehouse_id', $defaultWarehouseId)
    //             ->where('material_id', $reqMaterial->material_id)
    //             ->first();

    //         $stockLevel = $inventoryStock->current_stock ?? 0;

    //         if ($stockLevel < $quantityUsed) {
    //             // Jika stok kurang, kirim notifikasi ke tim Logistik
    //             $logisticUsers = User::whereHas('roles', fn($q) => $q->where('name', 'Logistic'))->get();

    //             if ($logisticUsers->isNotEmpty()) {
    //                 Notification::make()
    //                     ->title('Peringatan: Stok Material Tidak Cukup!')
    //                     ->body("Proyek '{$workItem->project->job_name}' butuh {$quantityUsed} {$reqMaterial->material->unit} {$reqMaterial->material->name}, tapi stok di gudang hanya {$stockLevel}.")
    //                     ->danger()
    //                     ->sendToDatabase($logisticUsers);
    //             }
    //             Log::warning("STOK TIDAK CUKUP untuk Material ID: {$reqMaterial->material_id} di Gudang ID: {$defaultWarehouseId}.");
    //         }

    //         // 5. Buat catatan pergerakan stok (tetap catat meskipun stok minus)
    //         StockMovement::create([
    //             'material_id' => $reqMaterial->material_id,
    //             'warehouse_id' => $defaultWarehouseId,
    //             'quantity' => -$quantityUsed, // Negatif karena stok keluar
    //             'type' => 'out',
    //             'remarks' => 'Digunakan untuk Proyek: ' . $workItem->project->job_name . ', Pekerjaan: ' . $workItem->name,
    //             'movable_id' => $workActivityLog->id,
    //             'movable_type' => get_class($workActivityLog),
    //             'user_id' => auth()->id() ?? $workActivityLog->created_by, // Ambil user yg login, atau fallback ke user yg buat laporan
    //         ]);
    //     }
    // }

    /**
     * Handle the WorkActivityLog "created" event.
     */
    public function created(WorkActivityLog $workActivityLog): void
    {
        // 1. Ambil data-data terkait
        $workItem = $workActivityLog->workItem;

        // Jika karena suatu hal item pekerjaan tidak ditemukan, hentikan.
        if (!$workItem) {
            Log::error('Observer GAGAL: WorkItem tidak ditemukan untuk WorkActivityLog ID: ' . $workActivityLog->id);
            return;
        }

        $project = $workItem->project;

        // 2. Tentukan gudang yang akan digunakan dari proyek induk
        $defaultWarehouseId = $project->default_warehouse_id;

        // Jika proyek belum memiliki gudang default, hentikan proses dan catat di log.
        if (is_null($defaultWarehouseId)) {
            Log::warning('PROSES STOK DIHENTIKAN: Proyek ID ' . $project->id . ' tidak memiliki gudang default.');
            return;
        }

        Log::info('Observer terpicu untuk Proyek: ' . $project->job_name . '. Stok akan dikurangi dari Gudang ID: ' . $defaultWarehouseId);

        // 3. Loop melalui semua material yang dibutuhkan untuk item pekerjaan ini
        foreach ($workItem->workItemMaterials as $reqMaterial) {

            // Hitung berapa banyak material yang digunakan untuk volume realisasi saat ini
            $quantityUsed = ($reqMaterial->quantity / $workItem->volume) * $workActivityLog->realized_volume;

            // 4. Periksa ketersediaan stok (Jaring Pengaman)
            $inventoryStock = InventoryStock::where('warehouse_id', $defaultWarehouseId)
                ->where('material_id', $reqMaterial->material_id)
                ->first();

            $stockLevel = $inventoryStock->current_stock ?? 0;

            if ($stockLevel < $quantityUsed) {
                // Jika stok kurang, kirim notifikasi ke tim Logistik
                $logisticUsers = User::whereHas('roles', fn($q) => $q->where('name', 'Logistic'))->get();

                if ($logisticUsers->isNotEmpty()) {
                    Notification::make()
                        ->title('Peringatan: Stok Material Tidak Cukup!')
                        ->body("Proyek '{$project->job_name}' butuh {$quantityUsed} {$reqMaterial->material->unit} {$reqMaterial->material->name}, tapi stok di gudang hanya {$stockLevel}.")
                        ->danger()
                        ->sendToDatabase($logisticUsers);
                }
                Log::warning("STOK TIDAK CUKUP untuk Material ID: {$reqMaterial->material_id} di Gudang ID: {$defaultWarehouseId}.");
            }

            // 5. Buat catatan pergerakan stok (tetap catat meskipun stok minus)
            StockMovement::create([
                'material_id' => $reqMaterial->material_id,
                'warehouse_id' => $defaultWarehouseId,
                'project_id' => $project->id,
                'quantity' => -$quantityUsed, // Negatif karena stok keluar
                'type' => 'out',
                'remarks' => 'Digunakan untuk Proyek: ' . $project->job_name . ', Pekerjaan: ' . $workItem->name,
                'movable_id' => $workActivityLog->id,
                'movable_type' => get_class($workActivityLog),
                'user_id' => auth()->id() ?? $workActivityLog->created_by,
            ]);
        }
    }

    /**
     * Handle the WorkActivityLog "updated" event.
     */
    public function updated(WorkActivityLog $workActivityLog): void
    {
        //
    }

    /**
     * Handle the WorkActivityLog "deleted" event.
     */
    public function deleted(WorkActivityLog $workActivityLog): void
    {
        //
    }

    /**
     * Handle the WorkActivityLog "restored" event.
     */
    public function restored(WorkActivityLog $workActivityLog): void
    {
        //
    }

    /**
     * Handle the WorkActivityLog "force deleted" event.
     */
    public function forceDeleted(WorkActivityLog $workActivityLog): void
    {
        //
    }
}

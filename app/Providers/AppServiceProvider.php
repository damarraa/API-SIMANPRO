<?php

namespace App\Providers;

use App\Models\MaterialRequisition;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use App\Models\ToolRequisition;
use App\Models\VehicleRequisition;
use App\Models\WorkActivityLog;
use App\Models\WorkItemLabors;
use App\Models\WorkItemMaterials;
use App\Observers\MaterialRequisitionObserver;
use App\Observers\PurchaseOrderObserver;
use App\Observers\StockMovementObserver;
use App\Observers\ToolRequisitionObserver;
use App\Observers\VehicleRequisitionObserver;
use App\Observers\WorkActivityLogObserver;
use App\Observers\WorkItemLaborObserver;
use App\Observers\WorkItemMaterialObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            // Jika user memiliki role 'Super Admin', ia otomatis diizinkan melakukan apa saja.
            return $user->hasRole('Super Admin') ? true : null;
        });

        WorkItemMaterials::observe(WorkItemMaterialObserver::class);
        WorkItemLabors::observe(WorkItemLaborObserver::class);
        StockMovement::observe(StockMovementObserver::class);
        WorkActivityLog::observe(WorkActivityLogObserver::class);
        PurchaseOrder::observe(PurchaseOrderObserver::class);
        MaterialRequisition::observe(MaterialRequisitionObserver::class);
        ToolRequisition::observe(ToolRequisitionObserver::class);
        VehicleRequisition::observe(VehicleRequisitionObserver::class);
    }
}

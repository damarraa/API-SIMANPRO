<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update Version 10/07/2025
        // 1. Reset cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Buat semua permission yang dibutuhkan oleh sistem
        $userPermissions = ['view_any::user', 'view::user', 'create::user', 'update::user', 'delete::user'];
        $rolePermissions = ['view_any::role', 'view::role', 'create::role', 'update::role', 'delete::role'];

        $projectPermissions = [
            'view_any::project',
            'view::project',
            'create::project',
            'update::project',
            'delete::project',
            'view_any::project_work_item',
            'create::project_work_item',
            'update::project_work_item',
            'delete::project_work_item',
            'view_any::daily_project_report',
            'create::daily_project_report',
            'update::daily_project_report',
            'delete::daily_project_report',
            'view_any::work_activity_log',
            'create::work_activity_log',
            'update::work_activity_log',
            'delete::work_activity_log',
            'view_any::project_expense',
            'create::project_expense',
            'update::project_expense',
            'delete::project_expense',
            'view_any::material_requisition',
            'create::material_requisition',
            'update::material_requisition',
            'delete::material_requisition',
        ];

        $inventoryPermissions = [
            'view_any::material',
            'create::material',
            'update::material',
            'delete::material',
            'view_any::tool',
            'create::tool',
            'update::tool',
            'delete::tool',
            'view_any::warehouse',
            'create::warehouse',
            'update::warehouse',
            'delete::warehouse',
            'view_any::supplier',
            'create::supplier',
            'update::supplier',
            'delete::supplier',
            'view_any::stock_movement',
            'create::stock_movement',
        ];

        $vehiclePermissions = [
            'view_any::vehicle',
            'view::vehicle',
            'create::vehicle',
            'update::vehicle',
            'delete::vehicle',
            'view_any::maintenance_log',
            'create::maintenance_log',
            'update::maintenance_log',
            'delete::maintenance_log',
            'view_any::vehicle_assignment',
            'create::vehicle_assignment',
            'update::vehicle_assignment',
            'delete::vehicle_assignment',
        ];

        $purchaseOrderPermissions = [
            'view_any::purchase_order', 
            'view::purchase_order', 
            'create::purchase_order', 
            'update::purchase_order', 
            'delete::purchase_order'
        ];

        // Gabungkan semua array permission dan buat di database
        $allPermissions = array_merge(
            $userPermissions, 
            $rolePermissions, 
            $projectPermissions, 
            $inventoryPermissions, 
            $vehiclePermissions,
            $purchaseOrderPermissions
        );
        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 3. Buat Roles
        $roleAdmin = Role::create(['name' => 'Admin']);
        $roleProjectManager = Role::create(['name' => 'Project Manager']);
        $roleSupervisor = Role::create(['name' => 'Supervisor']);
        $roleLogistic = Role::create(['name' => 'Logistic']);
        Role::create(['name' => 'Super Admin']);

        // 4. Berikan izin ke setiap role
        $roleAdmin->givePermissionTo([
            'view_any::user',
            'view::user',
            'create::user',
            'update::user',
            'view_any::role',
            'view::role',
            'create::role',
            'update::role',
            'view_any::project',
            'view_any::daily_project_report',
            'view_any::material',
            'view_any::tool',
            'view_any::warehouse',
            'view_any::supplier',
            'view_any::vehicle',
            'view::vehicle'
        ]);

        // Project Manager
        $roleProjectManager->givePermissionTo([
            'view_any::project',
            'view::project',
            'create::project',
            'update::project',
            'view_any::daily_project_report',
            'create::daily_project_report',
            'update::daily_project_report',
            'view_any::project_work_item',
            'create::project_work_item',
            'update::project_work_item',
            'delete::project_work_item',
            'view_any::material_requisition',
            'create::material_requisition',
        ]);

        // Supervisor
        $roleSupervisor->givePermissionTo([
            'view::project',
            'view_any::daily_project_report',
            'create::daily_project_report',
            'view_any::work_activity_log',
            'create::work_activity_log',
            'update::work_activity_log',
            'view_any::material_requisition',
            'create::material_requisition',
        ]);

        // Logistic
        $roleLogistic->givePermissionTo([
            'view_any::material',
            'create::material',
            'update::material',
            'view_any::tool',
            'create::tool',
            'update::tool',
            'view_any::warehouse',
            'create::warehouse',
            'update::warehouse',
            'view_any::supplier',
            'create::supplier',
            'update::supplier',
            'view_any::stock_movement',
            'create::stock_movement',
            'view_any::vehicle',
            'view::vehicle',
            'create::vehicle_assignment',
            'update::vehicle_assignment',
            'create::maintenance_log',
            'update::maintenance_log',
            'view_any::material_requisition',
            'update::material_requisition',
            'view_any::purchase_order',
            'create::purchase_order',
            'update::purchase_order',
        ]);
    }
}

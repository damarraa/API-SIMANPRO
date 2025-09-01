<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            UserSeeder::class,
            ClientSeeder::class,
            JobSeeder::class,
            SupplierSeeder::class,

            // Master Data yang bergantung pada Fondasi
            WarehouseSeeder::class,
            MaterialSeeder::class,
            ToolSeeder::class,

            // Data Utama yang bergantung pada Master Data
            ProjectSeeder::class,
            DailyProjectReportSeeder::class,
            InventoryStockSeeder::class,
        ]);
    }
}

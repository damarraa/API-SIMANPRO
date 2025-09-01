<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'teknisi_it@prisan.co.id',
            'password' => Hash::make('Prisan@1234'),
            'is_active' => true,
        ]);

        $superAdmin->assignRole('Super Admin');

        /**
         * 
         */
        // --- Dummy Project Manager ---
        $pm = User::create([
            'name' => 'PM ABC',
            'email' => 'pm@example.com',
            'password' => Hash::make('testing123'),
            'is_active' => true,
        ]);

        $pm->assignRole('Project Manager');

        // --- Dummy Logistic ---
        $logistic = User::create([
            'name' => 'Logistic ABC',
            'email' => 'log@example.com',
            'password' => Hash::make('testing123'),
            'is_active' => true,
        ]);

        $logistic->assignRole('Logistic');

        // --- Dummy Supervisor ---
        $spv = User::create([
            'name' => 'Spv ABC',
            'email' => 'spv@example.com',
            'password' => Hash::make('testing123'),
            'is_active' => true,
        ]);

        $spv->assignRole('Supervisor');
    }
}

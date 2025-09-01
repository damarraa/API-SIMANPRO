<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DailyProjectReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari proyek pertama yang ada
        $project = Project::first();
        // Cari user supervisor pertama yang ada
        $supervisor = User::whereHas('roles', fn($q) => $q->where('name', 'Supervisor'))->first();

        // Hanya jalankan jika proyek dan supervisor ditemukan
        if ($project && $supervisor) {
            $project->dailyReports()->create([
                'report_date' => now()->subDay(),
                'weather' => 'Cerah',
                'personnel_count' => 10,
                'submitted_by' => $supervisor->id,
                'notes' => 'Laporan harian dummy dari seeder terpisah.',
            ]);
        }
    }
}

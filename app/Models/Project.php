<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'contract_num',
        'job_name',
        'start_date',
        'end_date',
        'location',
        'latitude',
        'longitude',
        'contract_type',
        'fund_source',
        'contract_value',
        'total_budget',
        'status',
        'job_id',
        'client_id',
        'project_manager_id',
        'user_id',
        'default_warehouse_id'
    ];

    public function workItems(): HasMany
    {
        return $this->hasMany(ProjectWorkItem::class);
    }

    public function dailyReports(): HasMany
    {
        return $this->hasMany(DailyProjectReport::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(ProjectExpense::class);
    }

    // public function team(): BelongsToMany
    // {
    //     return $this->belongsToMany(User::class, 'project_user');
    // }
    public function team(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->withPivot('id', 'role_in_project', 'external_member_name')
            ->withTimestamps();
    }

    public function teamMembers(): HasMany
    {
        return $this->hasMany(ProjectTeamMember::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobType::class, 'job_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function defaultWarehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'default_warehouse_id');
    }

    /**
     * Accessor untuk menghitung total anggaran yang sudah digunakan.
     * Ini adalah versi yang optimal.
     */
    protected function spentBudget(): Attribute
    {
        return Attribute::make(get: function () {
            // 1. Hitung biaya dari realisasi pekerjaan (Optimal)
            $workItems = $this->workItems()->with('activityLogs')->get();

            $realized_work_total = $workItems->reduce(function ($carry, $item) {
                $totalRealizedVolume = $item->activityLogs->sum('realized_volume');
                // Asumsi `unit_price` ada di $item, jika tidak, perlu di-join atau di-load
                $unitPrice = $item->unit_price;
                return $carry + ($totalRealizedVolume * $unitPrice);
            }, 0);

            // 2. Hitung biaya operasional
            $other_expenses_total = $this->expenses()->sum('amount');

            return $realized_work_total + $other_expenses_total;
        });
    }

    /**
     * Accessor untuk menghitung sisa anggaran.
     */
    protected function remainingBudget(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->total_budget - $this->spent_budget
        );
    }

    public function vehicleAssignments()
    {
        return $this->hasMany(VehicleAssignment::class);
    }

    /**
     * Menampilkan kendaraan yang ditugaskan ke proyek
     */
    public function assignedVehicles()
    {
        return $this->hasManyThrough(
            Vehicle::class,
            VehicleAssignment::class,
            'project_id',
            'id',
            'id',
            'vehicle_id'
        );
    }
}

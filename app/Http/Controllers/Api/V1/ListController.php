<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Material;
use App\Models\Project;
use App\Models\Supplier;
use App\Models\Tool;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * Mengambil daftar semua klien.
     */
    public function clients()
    {
        return Client::select('id', 'client_name as name')->get();
    }

    /**
     * Mengambil daftar semua gudang.
     */
    public function warehouses()
    {
        return Warehouse::select('id', 'warehouse_name as name')->get();
    }

    /**
     * Mengambil daftar user dengan role 'Project Manager'.
     */
    public function projectManagers()
    {
        return User::whereHas('roles', fn($q) => $q->where('name', 'Project Manager'))
            ->select('id', 'name')->get();
    }

    /**
     * Mengambil daftar semua proyek.
     */
    public function projects(Request $request)
    {
        $user = $request->user();
        $query = Project::query();

        if ($user->hasRole('Project Manager')) {
            $query->where('project_manager_id', $user->id);
        } elseif ($user->hasRole('Supervisor')) {
            $query->whereHas('team', fn($q) => $q->where('user_id', $user->id));
        }
        return Project::select('id', 'job_name as name')->get();
    }

    /**
     * Mengambil daftar semua material.
     */
    public function materials()
    {
        return Material::select('id', 'name', 'unit')->get();
    }

    /**
     * Mengambil daftar semua alat.
     */
    public function tools()
    {
        return Tool::select('id', 'name')->get();
    }

    /**
     * Mengambil daftar semua user.
     */
    public function users()
    {
        return User::select('id', 'name')->get();
    }

    /**
     * Mengambil daftar user berdasarkan role spesifik.
     */
    public function usersByRole(string $roleName)
    {
        return User::whereHas('roles', fn($q) => $q->where('name', $roleName))->select('id', 'name')->get();
    }

    /**
     * Mengambil daftar semua supplier.
     */
    public function suppliers()
    {
        return Supplier::select('id', 'name')->get();
    }

    /**
     * Mengambil daftar semua kendaraan dan alat berat.
     */
    public function vehicles()
    {
        return Vehicle::select('id', 'name', 'license_plate')->get();
    }
}

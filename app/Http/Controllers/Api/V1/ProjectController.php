<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\VehicleAssignmentResource;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with(['projectManager', 'client', 'defaultWarehouse', 'job', 'team', 'assignedVehicles'])->get();
        return ProjectResource::collection($projects);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->validate());

        return (new ProjectResource($project->load(['client', 'projectManager', 'job', 'defaultWarehouse'])))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return new ProjectResource($project->load(['client', 'projectManager', 'job', 'defaultWarehouse', 'team', 'assignedVehicles']));
    }

    /**
     * Menampilkan semua penugasan
     * kendaraan dan alat berat untuk proyek.
     */
    public function vehicleAssignments(Project $project)
    {
        $this->authorize('view', $project);

        $assignments = $project->vehicleAssignments()->with(['vehicle', 'driver'])->get();
        return VehicleAssignmentResource::collection($assignments);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

        return new ProjectResource($project->load(['client', 'projectManager', 'job', 'defaultWarehouse']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return response()->noContent();
    }
}

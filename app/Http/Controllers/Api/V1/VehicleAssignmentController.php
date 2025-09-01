<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleAssignmentRequest;
use App\Http\Requests\UpdateVehicleAssignmentRequest;
use App\Http\Resources\VehicleAssignmentResource;
use App\Models\Vehicle;
use App\Models\VehicleAssignment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleAssignmentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Menampilkan semua penugasan dan bisa difilter per kendaraan jika
     * model $vehicle diberikan oleh route nested.
     */
    public function index(Request $request, Vehicle $vehicle = null)
    {
        $query = VehicleAssignment::with(['vehicle', 'project', 'driver'])->latest();

        if ($vehicle) {
            $this->authorize('view', $vehicle);
            $query->where('vehicle_id', $vehicle->id);
        } else {
            $this->authorize('viewAny', VehicleAssignment::class);
        }

        $assignments = $query->paginate();

        return VehicleAssignmentResource::collection($assignments);
    }

    /**
     * Original
     * Display a listing of the resource.
     */
    // public function index(Vehicle $vehicle)
    // {
    //     $assignments = $vehicle->assignments()->with(['project', 'driver'])->latest()->get();
    //     return VehicleAssignmentResource::collection($assignments);
    // }

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
    public function store(StoreVehicleAssignmentRequest $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);
        $validatedData = $request->validated();

        $assignment = $vehicle->assignments()->create($validatedData);
        return (new VehicleAssignmentResource($assignment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    // Original
    // public function store(StoreVehicleAssignmentRequest $request)
    // {
    //     $assignment = VehicleAssignment::create($request->validated());
    //     return (new VehicleAssignmentResource($assignment))
    //         ->response()
    //         ->setStatusCode(Response::HTTP_CREATED);
    // }

    /**
     * Display the specified resource.
     */
    public function show(VehicleAssignment $vehicleAssignment)
    {
        return new VehicleAssignmentResource($vehicleAssignment->load(['vehicle', 'project', 'driver']));
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
    public function update(UpdateVehicleAssignmentRequest $request, VehicleAssignment $vehicleAssignment)
    {
        $this->authorize('update', $vehicleAssignment);
        $vehicleAssignment->update($request->validated());
        return new VehicleAssignmentResource($vehicleAssignment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VehicleAssignment $vehicleAssignment)
    {
        $this->authorize('delete', $vehicleAssignment);
        $vehicleAssignment->delete();
        return response()->noContent();
    }
}

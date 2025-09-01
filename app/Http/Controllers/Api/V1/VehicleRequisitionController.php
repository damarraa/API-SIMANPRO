<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequisitionRequest;
use App\Http\Resources\VehicleRequisitionResource;
use App\Models\VehicleRequisition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class VehicleRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requisitions = VehicleRequisition::with(['project', 'requester'])->latest()->get();
        return VehicleRequisitionResource::collection($requisitions);
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
    public function store(StoreVehicleRequisitionRequest $request)
    {
        $validatedData = $request->validated();

        $vehicleRequisition = DB::transaction(function () use ($validatedData) {
            $prefix = 'VR';
            $datePrefix = Carbon::now()->format('Ym');
            $count = VehicleRequisition::where('vr_number', 'like', "{$prefix}-{$datePrefix}-%")->count();
            $nextNumber = $count + 1;
            $paddedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $vrNumber = "{$prefix}-{$datePrefix}-{$paddedNumber}";

            $vr = VehicleRequisition::create([
                'vr_number' => $vrNumber,
                'project_id' => $validatedData['project_id'],
                'request_date' => $validatedData['request_date'],
                'notes' => $validatedData['notes'] ?? null,
                'status' => 'Pending',
                'requested_by' => auth()->id(),
            ]);

            $vr->items()->createMany($validatedData['items']);

            return $vr;
        });

        return (new VehicleRequisitionResource($vehicleRequisition->load(['project', 'items.vehicle'])))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(VehicleRequisition $vehicleRequisition)
    {
        return new VehicleRequisitionResource($vehicleRequisition->load(['project', 'requester', 'items.vehicle']));
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
    public function update(Request $request, VehicleRequisition $vehicleRequisition)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Approved,Rejected,Issued,Returned',
        ]);

        $vehicleRequisition->update($validated);

        return new VehicleRequisitionResource($vehicleRequisition);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

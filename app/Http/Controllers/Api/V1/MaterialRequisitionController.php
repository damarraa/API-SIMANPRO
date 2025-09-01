<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaterialRequisitionRequest;
use App\Http\Resources\MaterialRequisitionResource;
use App\Models\MaterialRequisition;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MaterialRequisitionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        $requisitions = $project->materialRequisitions()->with('requester')->latest()->get();
        return MaterialRequisitionResource::collection($requisitions);

        // Original
        // $requisitions = MaterialRequisition::with(['project', 'requester'])->latest()->get();
        // return MaterialRequisitionResource::collection($requisitions);
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
    public function store(StoreMaterialRequisitionRequest $request, Project $project)
    {
        $this->authorize('update', $project);
        $validatedData = $request->validated();

        $materialRequisition = DB::transaction(function () use ($validatedData, $project) {
            // Nomor permintaan barang
            $prefix = 'MR';
            $datePrefix = Carbon::now()->format('Ym');
            $count = MaterialRequisition::where('mr_number', 'like', "{$prefix}-{$datePrefix}-%")->count();
            $nextNumber = $count + 1;
            $paddedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $mrNumber = "{$prefix}-{$datePrefix}-{$paddedNumber}";

            // $mr = MaterialRequisition::create([
            $mr = $project->materialRequisitions()->create([
                // 'mr_number' => 'MR-' . date('Ymd') . '-' . mt_rand(1000, 9999), // Sementara contoh nomor random
                'mr_number' => $mrNumber,
                'project_id' => $validatedData['project_id'],
                'request_date' => $validatedData['request_date'],
                'notes' => $validatedData['notes'] ?? null,
                'status' => 'Pending',
                'requested_by' => auth()->id(),
            ]);

            $mr->items()->createMany($validatedData['items']);
            return $mr;
        });

        return (new MaterialRequisitionResource($materialRequisition->load(['project', 'items.material'])))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Materialrequisition $materialRequisition)
    {
        return new MaterialRequisitionResource($materialRequisition->load(['project', 'requester', 'items.material']));
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
    public function update(Request $request, MaterialRequisition $materialRequisition)
    {
        // Fokus perubahan status saat 'Issued', Observer akan berjalan
        $validated = $request->validate([
            'status' => 'required|string|in:Approved,Rejected,Issued',
            'notes' => 'nullable|string',
        ]);

        $materialRequisition->update($validated);

        return new MaterialRequisitionResource($materialRequisition);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

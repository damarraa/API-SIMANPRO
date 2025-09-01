<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreToolRequisitionRequest;
use App\Http\Resources\ToolRequisitionResource;
use App\Models\ToolRequisition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ToolRequisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requisitions = ToolRequisition::with(['project', 'requester'])->latest()->get();
        return ToolRequisitionResource::collection($requisitions);
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
    public function store(StoreToolRequisitionRequest $request)
    {
        $validatedData = $request->validated();

        $toolRequisition = DB::transaction(function () use ($validatedData) {
            // Nomor permintaan alat
            $prefix = 'TR';
            $datePrefix = Carbon::now()->format('Ym');
            $count = ToolRequisition::where('tr_number', 'like', "{$prefix}-{$datePrefix}-%")->count();
            $nextNumber = $count + 1;
            $paddedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $trNumber = "{$prefix}-{$datePrefix}-{$paddedNumber}";

            $tr = ToolRequisition::create([
                'tr_number' => $trNumber,
                'project_id' => $validatedData['project_id'],
                'request_date' => $validatedData['request_date'],
                'notes' => $validatedData['notes'] ?? null,
                'status' => 'Pending',
                'requested_by' => auth()->id(),
            ]);

            $tr->items()->createMany($validatedData['items']);
            return $tr;
        });

        return (new ToolRequisitionResource($toolRequisition->load(['project', 'items.tool'])))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(ToolRequisition $toolRequisition)
    {
        return new ToolRequisitionResource($toolRequisition->load(['project', 'requester', 'items.tool']));
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
    public function update(Request $request, ToolRequisition $toolRequisition)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Approved,Rejected,Issued,Returned',
        ]);

        $toolRequisition->update($validated);
        return new ToolRequisitionResource($toolRequisition);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

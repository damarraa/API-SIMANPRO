<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetAssignmentRequest;
use App\Http\Requests\UpdateAssetAssignmentRequest;
use App\Http\Resources\AssetAssignmentResource;
use App\Models\AssetAssignment;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AssetAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Tool $tool)
    {
        $assignments = $tool->assignments()->with(['tool', 'project', 'assignedBy'])->latest()->get();
        return AssetAssignmentResource::collection($assignments);
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
    public function store(StoreAssetAssignmentRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['assigned_by'] = auth()->id();

        $assignment = AssetAssignment::create($validatedData);

        return (new AssetAssignmentResource($assignment))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetAssignment $assetAssignment)
    {
        return new AssetAssignmentResource($assetAssignment->load(['tool', 'project', 'assignedBy']));
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
    public function update(UpdateAssetAssignmentRequest $request, AssetAssignment $assetAssignment)
    {
        $assetAssignment->update($request->validated());

        return new AssetAssignmentResource($assetAssignment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetAssignment $assetAssignment)
    {
        $this->authorize('delete', $assetAssignment);

        $assetAssignment->delete();

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetLocationRequest;
use App\Http\Resources\AssetLocationResource;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class AssetLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Tool $tool)
    {
        $locations = $tool->locations()->with('warehouse')->get();
        return AssetLocationResource::collection($locations);
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
    public function store(StoreAssetLocationRequest $request, Tool $tool)
    {
        $location = $tool->locations()->create($request->validated());

        return (new AssetLocationResource($location))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

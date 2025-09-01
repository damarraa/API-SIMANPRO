<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkActivityLogRequest;
use App\Http\Requests\UpdateWorkActivityLogRequest;
use App\Http\Resources\WorkActivityLogResource;
use App\Models\DailyProjectReport;
use App\Models\WorkActivityLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WorkActivityLogController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Display a listing of the resource.
     */
    public function index(DailyProjectReport $dailyReport)
    {
        return WorkActivityLogResource::collection($dailyReport->workActivities()->with('workItem')->get());
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
    public function store(StoreWorkActivityLogRequest $request, DailyProjectReport $dailyReport)
    {
        $validatedData = $request->validated();
        $validatedData['created_by'] = auth()->id();

        $activityLog = $dailyReport->workActivities()->create($validatedData);

        // Observer untuk mengurangi stok material akan berjalan di sini
        return (new WorkActivityLogResource($activityLog))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkActivityLog $activityLog) // Menggunakan shallow binding
    {
        return new WorkActivityLogResource($activityLog->load('workItem'));
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
    public function update(UpdateWorkActivityLogRequest $request, WorkActivityLog $activityLog)
    {
        $this->authorize('update', $activityLog);
        $activityLog->update($request->validated());
        return new WorkActivityLogResource($activityLog);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkActivityLog $activityLog)
    {
        $this->authorize('delete', $activityLog);
        $activityLog->delete();
        return response()->noContent();
    }
}

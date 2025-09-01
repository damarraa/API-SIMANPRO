<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDailyProjectReportRequest;
use App\Http\Requests\UpdateDailyProjectReportRequest;
use App\Http\Resources\DailyProjectReportResource;
use App\Models\DailyProjectReport;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DailyProjectReportController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $reports = $project->dailyReports()->with('submittedBy')->latest()->get();
        return DailyProjectReportResource::collection($reports);
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
    public function store(StoreDailyProjectReportRequest $request, Project $project)
    {
        /**
         * Update 11/07/2025
         * Penambahan aturan baru
         */
        // Panggil method 'addDailyReport' dari ProjectPolicy
        // untuk proyek spesifik ini ($project)
        $this->authorize('addDailyReport', $project);

        // Jika lolos otorisasi, sisa kodenya berjalan seperti biasa
        $validatedData = $request->validated();
        $validatedData['submitted_by'] = auth()->id();

        $dailyReport = $project->dailyReports()->create($validatedData);

        return (new DailyProjectReportResource($dailyReport))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);

        // Original Version
        // $validatedData = $request->validated();
        // $validatedData['submitted_by'] = auth()->id();

        // $dailyReport = $project->dailyReports()->create($validatedData);

        // return (new DailyProjectReportResource($dailyReport))
        //     ->response()
        //     ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(DailyProjectReport $dailyReport)
    {
        return new DailyProjectReportResource($dailyReport->load(['project', 'submittedBy', 'workActivities']));
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
    public function update(UpdateDailyProjectReportRequest $request, DailyProjectReport $dailyReport)
    {
        $this->authorize('update', $dailyReport);
        $dailyReport->update($request->validated());
        return new DailyProjectReportResource($dailyReport);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DailyProjectReport $dailyReport)
    {
        $this->authorize('delete', $dailyReport);
        $dailyReport->delete();
        return response()->noContent();
    }
}

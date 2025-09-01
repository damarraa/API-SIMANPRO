<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaintenanceLogRequest;
use App\Http\Requests\UpdateMaintenanceLogRequest;
use App\Http\Resources\MaintenanceLogResource;
use App\Models\MaintenanceLog;
use App\Models\Vehicle;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class MaintenanceLogController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Vehicle $vehicle)
    {
        $logs = $vehicle->maintenanceLogs()->latest()->get();
        return MaintenanceLogResource::collection($logs);
    }

    // Original
    // public function index()
    // {
    //     $logs = MaintenanceLog::with('vehicle')->latest()->get();
    //     return MaintenanceLogResource::collection($logs);
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
    public function store(StoreMaintenanceLogRequest $request, Vehicle $vehicle): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update', $vehicle);

        $validatedData = $request->validated();

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $fileName = 'maintenance-' . $vehicle->id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $directory = 'maintenance-docs';
            $path = $directory . '/' . $fileName;

            $processedImage = Image::read($file)
                ->scale(width: 1080)
                ->toJpeg(quality: 75);

            Storage::disk('public')->put($path, (string) $processedImage);
            $validatedData['docs_path'] = $path;
        }

        $log = $vehicle->maintenanceLogs()->create($validatedData);

        return (new MaintenanceLogResource($log))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    // public function store(StoreMaintenanceLogRequest $request): \Illuminate\Http\JsonResponse
    // {
    //     $validatedData = $request->validated();

    //     if ($request->hasFile('document')) {
    //         $file = $request->file('document');
    //         $fileName = 'maintenance-' . $validatedData['vehicle_id'] . '-' . time() . '.' . $file->getClientOriginalExtension();
    //         $directory = 'maintenance-docs';
    //         $path = $directory . '/' . $fileName;

    //         $processedImage = Image::read($file)
    //             ->scale(width: 1080)
    //             ->toJpeg(quality: 75);

    //         Storage::disk('public')->put($path, $processedImage);
    //         $validatedData['docs_path'] = $path;
    //     }

    //     $log = MaintenanceLog::create($validatedData);

    //     return (new MaintenanceLogResource($log))
    //         ->response()
    //         ->setStatusCode(Response::HTTP_CREATED);
    // }

    /**
     * Display the specified resource.
     */
    public function show(MaintenanceLog $maintenanceLog)
    {
        return new MaintenanceLogResource($maintenanceLog->load('vehicle'));
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
    public function update(UpdateMaintenanceLogRequest $request, MaintenanceLog $maintenanceLog)
    {
        $this->authorize('update', $maintenanceLog);

        $validatedData = $request->validated();

        if ($request->hasFile('document')) {
            if ($maintenanceLog->docs_path) {
                Storage::disk('public')->delete($maintenanceLog->docs_path);
            }

            $file = $request->file('document');
            $fileName = 'maintenance-' . $maintenanceLog->vehicle_id . '-' . time() . '.' . $file->getClientOriginalExtension();
            $directory = 'maintenance-docs';
            $path = $directory . '/' . $fileName;

            $processedImage = Image::read($file)
                ->scale(width: 1080)
                ->toJpeg(quality: 75);

            Storage::disk('public')
                ->put($path, (string) $processedImage);

            $validatedData['docs_path'] = $path;
        }

        $maintenanceLog->update($validatedData);
        return new MaintenanceLogResource($maintenanceLog);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MaintenanceLog $maintenanceLog)
    {
        $this->authorize('delete', $maintenanceLog);

        if ($maintenanceLog->docs_path) {
            Storage::disk('public')->delete($maintenanceLog->docs_path);
        }

        $maintenanceLog->delete();
        return response()->noContent();
    }
}

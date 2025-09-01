<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Menggunakan $this->whenLoaded() adalah best practice untuk memastikan relasi hanya
        // disertakan jika sudah di-load dari controller, mencegah N+1 query problem.
        return [
            'id' => $this->id,
            'contract_number' => $this->contract_num,
            'project_name' => $this->job_name,
            'status' => $this->status,
            'location' => $this->location,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'contract_value' => (float) $this->contract_value,
            'total_budget' => (float) $this->total_budget,
            'fund_source' => $this->fund_source,
            'contract_type' => $this->contract_type,

            // Data dari Relasi
            'project_manager' => $this->whenLoaded('projectManager', fn() => $this->projectManager->name),
            'client' => $this->whenLoaded('client', fn() => $this->client->client_name),
            'default_warehouse' => $this->whenLoaded('defaultWarehouse', fn() => $this->defaultWarehouse->warehouse_name),
            'job_category' => $this->whenLoaded('job', fn() => $this->job->job_type),
            'team' => UserResource::collection($this->whenLoaded('team')),
            'assigned_vehicles' => VehicleResource::collection($this->whenLoaded('assignedVehicles')),
        ];
    }
}

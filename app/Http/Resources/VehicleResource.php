<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'vehicle_type' => $this->vehicle_type,
            'merk' => $this->merk,
            'model' => $this->model,
            'license_plate' => $this->license_plate,
            'purchase_year' => $this->purchase_year,
            'capacity' => $this->capacity,
            'vehicle_license' => $this->vehicle_license,
            'license_expiry_date' => $this->license_expiry_date,
            'vehicle_identity_number' => $this->vehicle_identity_number,
            'engine_number' => $this->engine_number,
            'tax_due_date' => $this->tax_due_date,
            'notes' => $this->notes,
            'docs_path' => $this->docs_path ? Storage::url($this->docs_path) : null,

            'pic' => $this->whenLoaded('pic', fn() => [
                'id' => $this->pic->id,
                'name' => $this->pic->name,
            ]),
        ];
    }
}

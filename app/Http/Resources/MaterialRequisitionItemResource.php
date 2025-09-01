<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialRequisitionItemResource extends JsonResource
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
            'quantity_requested' => (float) $this->quantity_requested,
            'quantity_issued' => (float) $this->quantity_issued,
            'material' => $this->whenLoaded('material', fn() => [
                'id' => $this->material->id,
                'name' => $this->material->name,
                'sku' => $this->material->sku,
            ]),
        ];
    }
}

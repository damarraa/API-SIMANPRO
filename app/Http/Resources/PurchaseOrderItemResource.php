<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderItemResource extends JsonResource
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
            'quantity' => (float) $this->quantity,
            'unit_price' => (float) $this->unit_price,
            'total_price' => (float) $this->quantity * $this->unit_price,
            'material' => $this->whenLoaded('material', fn() => [
                'id' => $this->material->id,
                'name' => $this->material->name,
                'sku' => $this->material->sku,
            ]),
        ];
    }
}

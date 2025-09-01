<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryStockResource extends JsonResource
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
            'current_stock' => (float) $this->current_stock,
            'min_stock' => (float) $this->min_stock,
            'material' => [
                'id' => $this->material->id,
                'name' => $this->material->name,
                'sku' => $this->material->sku,
                'unit' => $this->material->unit,
            ],
        ];
    }
}

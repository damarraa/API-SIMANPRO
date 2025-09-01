<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetLocationResource extends JsonResource
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
            'quantity' => $this->quantity,
            'last_moved_at' => $this->last_moved_at,
            'warehouse' => [
                'id' => $this->warehouse->id,
                'name' => $this->warehouse->warehouse_name,
            ],
        ];
    }
}

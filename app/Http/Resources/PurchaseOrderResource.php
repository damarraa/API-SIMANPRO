<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
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
            'po_number' => $this->po_number,
            'order_date' => $this->order_date,
            'expected_delivery_date' => $this->expected_delivery_date,
            'status' => $this->status,
            'notes' => $this->notes,

            // Relasi
            'supplier' => $this->whenLoaded('supplier', fn() => [
                'id' => $this->supplier->id,
                'name' => $this->supplier->name,
            ]),
            'warehouse' => $this->whenLoaded('warehouse', fn() => [
                'id' => $this->warehouse->id,
                'name' => $this->warehouse->warehouse_name,
            ]),
            'created_by' => $this->whenLoaded('creator', fn() => $this->creator->name),

            // Rincian Item (menggunakan PurchaseOrderItemResource)
            'items' => PurchaseOrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ToolRequisitionItemResource extends JsonResource
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
            'quantity_requested' => $this->quantity_requested,
            'tool' => $this->whenLoaded('tool', fn() => [
                'id' => $this->tool->id,
                'name' => $this->tool->name,
                'tool_code' => $this->tool->tool_code,
            ]),
        ];
    }
}

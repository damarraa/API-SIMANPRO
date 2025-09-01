<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ToolRequisitionResource extends JsonResource
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
            'tr_number' => $this->tr_number,
            'request_date' => $this->request_date,
            'status' => $this->status,
            'notes' => $this->notes,

            'project' => $this->whenLoaded('project', fn() => [
                'id' => $this->project->id,
                'name' => $this->project->job_name,
            ]),
            'requested_by' => $this->whenLoaded('requester', fn() => $this->requester->name),
            'items' => ToolRequisitionItemResource::collection($this->whenLoaded('items')),
        ];
    }
}

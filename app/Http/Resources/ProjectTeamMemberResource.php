<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectTeamMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->user->id ?? null,
            'name' => $this->user->name ?? null,
            'email' => $this->user->email ?? null,

            'pivot' => [
                'id' => $this->id,
                'role_in_project' => $this->role_in_project,
                'external_member_name' => $this->external_member_name,
            ]
            // Original v1
            // 'pivot_id' => $this->id,
            // 'role_in_project' => $this->role_in_project,
            // 'external_member_name' => $this->external_member_name,
            // 'user' => $this->whenLoaded('user', new UserResource($this->user)),
        ];
    }
}

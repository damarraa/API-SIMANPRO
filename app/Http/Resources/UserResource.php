<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'address' => $this->address,
            'phone' => $this->phone,
            'is_active' => (bool) $this->is_active,
            'last_active_at' => $this->last_active_at,
            'profile_picture_url' => $this->profile_picture ? Storage::url($this->profile_picture) : null,
            'signature_url' => $this->signature ? Storage::url($this->signature) : null,
            'roles' => $this->whenLoaded('roles', fn() => $this->roles->pluck('name')),

            'pivot' => $this->whenPivotLoaded('project_user', function () {
                return [
                    'role_in_project' => $this->pivot->role_in_project,
                    'external_member_name' => $this->pivot->external_member_name,
                ];
            }),
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\VehicleAssignment;
use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', VehicleAssignment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'vehicle_id' => 'required|integer|exists:vehicles,id',
            'user_id' => 'required|integer|exists:users,id',
            'project_id' => 'required|integer|exists:projects,id',
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date',
            'start_odometer' => 'required|string|max:255',
            'end_odometer' => 'nullable|string',
            'notes' => 'nullable|string',
        ];
    }
}

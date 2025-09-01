<?php

namespace App\Http\Requests;

use App\Models\MaintenanceLog;
use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', MaintenanceLog::class);
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
            'maintenance_date' => 'required|date',
            'odometer' => 'required|integer|min:0',
            'type' => 'required|string|in:Rutin,Insidental,Darurat',
            'location' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\StockMovement;
use Illuminate\Foundation\Http\FormRequest;

class StoreStockMovementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        return $this->user()->can('create', StockMovement::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'material_id' => 'required|integer|exists:materials,id',
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'project_id' => 'nullable|integer|exists:projects,id',
            'quantity' => 'required|numeric|min:0.01',
            'type' => 'required|in:in,out,return,adjustment',
            'remarks' => 'nullable|string|max:1000'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('purchase_order'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Data utama PO
            'supplier_id' => 'sometimes|required|integer|exists:suppliers,id',
            'warehouse_id' => 'sometimes|required|integer|exists:warehouses,id',
            'order_date' => 'sometimes|required|date',
            'expected_delivery_date' => 'sometimes|nullable|date|after_or_equal:order_date',
            'notes' => 'sometimes|nullable|string',
            'status' => 'sometimes|required|string|in:Draft,Submitted,Completed,Cancelled',

            // Rincian
            'items' => 'sometimes|array|min:1',
            'items.*.id' => 'nullable|integer|exists:purchase_order_items,id',
            'items.*.material_id' => 'required|integer|exists:materials,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
    }
}

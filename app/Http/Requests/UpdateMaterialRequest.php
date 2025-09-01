<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMaterialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
        return $this->user()->can('update', $this->route('material'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $materialId = $this->route('material')->id;

        return [
            'sku' => 'sometimes|required|string|max:255|unique:materials,sku,' . $materialId,
            'name' => 'sometimes|required|string|max:255',
            'unit' => 'sometimes|required|string|max:100',
            'description' => 'sometimes|nullable|string',
            'is_dpt' => 'sometimes|required|boolean',
            'supplier_id' => 'sometimes|nullable|integer|exists:suppliers,id',
            'picture' => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}

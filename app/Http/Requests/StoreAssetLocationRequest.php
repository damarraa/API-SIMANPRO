<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssetLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('tool'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $tool = $this->route('tool');

        return [
            'warehouse_id' => [
                'required',
                'integer',
                'exists:warehouses,id',
                // Aturan unik: Mencegah satu alat didaftarkan dua kali di gudang yang sama
                Rule::unique('asset_locations')->where('tool_id', $tool->id)
            ],
            'quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'warehouse_id.unique' => 'Alat ini sudah terdaftar di gudang yang dipilih.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaterialRequisitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            /**
             * Validasi data utama permintaan barang / MR
             * Tidak menggunakan project_id karena perubahan route nested 21/08/25
             */

            // 'project_id' => 'required|integer|exists:projects,id',
            'request_date' => 'required|date',
            'notes' => 'nullable|string',

            // Validasi untuk rincian item (array)
            'items' => 'required|array|min:1',
            'items.*.material_id' => 'required|integer|exists:materials,id',
            'items.*.quantity_requested' => 'required|numeric|min:0.01',
        ];
    }
}

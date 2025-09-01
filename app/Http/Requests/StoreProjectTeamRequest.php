<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProjectTeamRequest extends FormRequest
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
        // Changes 29/08/25
        return [
            'project_id' => 'required|integer|exists:projects,id',
            'user_id' => 'nullable|integer|exists:users,id|required_without:external_member_name',
            'external_member_name' => 'nullable|string|max:255|required_without:user_id',
            'role_in_project' => 'required|string|max:255',
        ];
    }

    // Original v1
    // public function rules(): array
    // {
    //     return [
    //         /**
    //          * user_id boleh kosong, tapi wajib diisi jika external_member_name tidak ada.
    //          * external_member_name boleh kosong, tapi wajib diisi jika user_id tidak ada.
    //          * role_in_project sekarang selalu wajib
    //          */
    //         // 'user_id' => 'nullable|integer|exists:users,id|required_without:external_member_name',
    //         'user_id' => [
    //             'nullable',
    //             'integer',
    //             'exists:users,id',
    //             Rule::requiredIf(empty($this->input('external_member_name')))
    //         ],
    //         // 'external_member_name' => 'nullable|string|max:255|required_without:user_id',
    //         'external_member_name' => [
    //             'nullable',
    //             'string',
    //             'max:255',
    //             Rule::requiredIf(empty($this->input('user_id')))
    //         ],

    //         'role_in_project' => 'required|string|max:255',
    //     ];
    // }

    public function messages(): array
    {
        // Pesan error kustom
        return [
            'user_id.required_if' => 'Pilih anggota tim dari sistem atau isi nama anggota manual.',
            'external_member_name.required_if' => 'Isi nama anggota manual atau pilih anggota tim dari sistem.',
        ];
    }
}

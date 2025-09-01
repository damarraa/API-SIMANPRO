<?php

namespace App\Http\Requests;

use App\Models\JobType;
use Illuminate\Foundation\Http\FormRequest;

class StoreJobTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', JobType::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'job_type' => 'required|string|max:255|unique:job_types,job_type',
        ];
    }
}

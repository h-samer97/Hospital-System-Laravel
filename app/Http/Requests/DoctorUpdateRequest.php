<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class DoctorUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', Rule::unique('doctors', 'email')->ignore($this->route('doctor'))],
            'password' => 'sometimes|required|string|min:8',
            'phone' => 'sometimes|required|string|max:20',
            'specialization' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'section_id' => 'sometimes|required|exists:sections,id',
        ];
    }
}

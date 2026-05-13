<?php

namespace App\Http\Requests\dashboard;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:35'],
            'email' => ['required', 'email', 'unique:doctors,email'],
            'phone' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'password' => ['required', 'string', 'min:8'],
            'is_active' => ['boolean'],
            'section_id' => ['required', 'integer', 'exists:sections,id'],
            'appointments' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 35 characters.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'phone.required' => 'The phone is required.',
            'phone.string' => 'The phone must be a string.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a number.',
            'password.required' => 'The password is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'is_active.boolean' => 'The is_active must be a boolean.',
            'section_id.required' => 'The section is required.',
            'section_id.integer' => 'The section_id must be an integer.',
            'section_id.exists' => 'The selected section is invalid.',
            'appointments.required' => 'The appointments is required.',
            'appointments.string' => 'The appointments must be a string.',
        ];
    }
}

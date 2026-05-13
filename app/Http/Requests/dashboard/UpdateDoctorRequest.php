<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorRequest extends FormRequest
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
            'id' => ['integer', 'required'],
            'name' => ['string', 'max:35', 'required'],
            'email' => ['email', 'required'],
            'phone' => ['string', 'required'],
            'price' => ['numeric', 'required'],
            'is_active' => ['boolean', 'required'],
            'section_id' => ['integer', 'required'],
            'appointments' => ['string', 'required'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 35 characters.',
            'name.required' => 'The name is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.required' => 'The email is required.',
            'phone.string' => 'The phone must be a string.',
            'phone.required' => 'The phone is required.',
            'price.numeric' => 'The price must be a number.',
            'price.required' => 'The price is required.',
            'is_active.boolean' => 'The is_active must be a boolean.',
            'is_active.required' => 'The is_active is required.',
            'section_id.integer' => 'The section_id must be an integer.',
            'section_id.required' => 'The section_id is required.',
            'appointments.string' => 'The appointments must be a string.',
            'appointments.required' => 'The appointments is required.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'name',
            'email' => 'email',
            'phone' => 'phone',
            'price' => 'price',
            'is_active' => 'is active',
            'section_id' => 'section',
            'appointments' => 'appointments',
        ];
    }

}

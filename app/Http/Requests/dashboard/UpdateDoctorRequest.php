<?php

namespace App\Http\Requests\dashboard;

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
            'email' => ['email', 'required', 'unique:doctors,email,' . $this->id],
            'phone' => ['string', 'required'],
            'price' => ['numeric', 'required'],
            'password' => ['nullable', 'string', 'min:8'],
            'is_active' => ['boolean'],
            'section_id' => ['integer', 'required'],
            'appointment_ids'  => ['required', 'array', 'min:1'],
            'appointment_ids.*'=> ['exists:appointments,id'],
            'image'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048']
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
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'section_id.integer' => 'The section_id must be an integer.',
            'section_id.required' => 'The section is required.',
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
        ];
    }

}

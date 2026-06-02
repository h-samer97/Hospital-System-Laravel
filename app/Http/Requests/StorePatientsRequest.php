<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePatientsRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:20'],
            'email' => ['required', 'email', 'unique:patients,email'],
            'password' => $this->isMethod('post') ? ['required', 'string', 'min:8'] : ['nullable', 'string', 'min:8'],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'numeric'],
            'gender' => ['required', 'in:1,2'],
            'blood_group' => ['required', 'in:O-,O+,A-,A+,B-,B+,AB-,AB+'],
            'address' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.min' => 'The name must be at least 2 characters.',
            'name.max' => 'The name may not be greater than 20 characters.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'birth_date.required' => 'The birth date field is required.',
            'birth_date.date' => 'The birth date must be a valid date.',
            'birth_date.before' => 'The birth date must be before today.',
            'phone.required' => 'The phone field is required.',
            'phone.numeric' => 'The phone must be a numeric value.',
            'gender.required' => 'The gender field is required.',
            'gender.in' => 'The gender must be either 1 or 2.',
            'blood_group.required' => 'The blood group field is required.',
            'blood_group.in' => 'The blood group must be a valid option.',
            'address.required' => 'The address field is required.',
            'address.string' => 'The address must be a string.',
            'address.max' => 'The address may not be greater than 500 characters.',
        ];
    }
}

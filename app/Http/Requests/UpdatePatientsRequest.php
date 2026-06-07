<?php

namespace App\Http\Requests;

use App\Models\Patients;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientsRequest extends FormRequest
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
            'email' => ['required', 'email', 'unique:patients,email', Rule::unique(Patients::class, 'email')->ignore($this->patient)],
            'password' => $this->isMethod('post') ? ['required', 'string', 'min:8'] : ['nullable', 'string', 'min:8'],
            'birth_date' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'numeric'],
            'gender' => ['required', 'in:male,female'],
            'blood_group' => ['required', 'in:O-,O+,A-,A+,B-,B+,AB-,AB+'],
            'address' => ['required', 'string', 'max:500'],
        ];
    }
}

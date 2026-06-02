<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAmbulanceRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'car_number' => ['required', 'string', 'min:5', 'max:20', Rule::unique('ambulances', 'car_number')
                ->ignore($this->route('ambulance')?->id ?? $this->route('ambulance'))],
            'car_model' => ['required', 'string'],
            'car_year_made' => ['required', 'integer', 'digits:4', 'min:1990', 'max:' . (\date('Y') + 1)],
            'driver_name' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'driver_license_number' => ['required', 'string'],
            'is_available' => ['boolean', 'required'],
            'status' => ['boolean', 'required'],

        ];
    }

    public function messages(): array
    {
        return [
            'car_number.required' => 'The ambulance number is required.',
            'car_number.string' => 'The ambulance number must be text.',
            'car_number.min' => 'The ambulance number must be at least 5 characters.',
            'car_number.max' => 'The ambulance number must not exceed 20 characters.',
            'car_model.required' => 'The ambulance model is required.',
            'car_year_made.required' => 'The year the ambulance was made is required.',
            'car_year_made.integer' => 'The year the ambulance was made must be an integer.',
            'car_year_made.digits' => 'The year the ambulance was made must be a 4-digit year.',
            'car_year_made.min' => 'The year the ambulance was made must be at least 1990.',
            'car_year_made.max' => 'The year the ambulance was made cannot be in the future.',
            'driver_name.required' => 'The driver\'s name is required.',
            'driver_license_number.required' => 'The driver\'s license number is required.',
            'is_available.boolean' => 'The availability status must be true or false.',
            'is_available.required' => 'The availability status is required.',
            'status.boolean' => 'The operational status must be true or false.',
            'status.required' => 'The operational status is required.',
        ];
    }
}

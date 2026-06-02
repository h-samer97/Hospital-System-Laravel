<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInsuranceRequest extends FormRequest
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
            #                                          unique: (table name)(column name)
            'insurance_code' => ['required', 'string', 'unique:insurances,insurance_code'],
            'name'          => ['required', 'string', 'unique:insurances,name'],
            'note'          => ['nullable', 'string', 'min:5', 'max:255'],
            'discount_percentage' => ['numeric', 'min:0', 'max:100'],
            'company_rate' => ['numeric', 'min:0', 'max:100'],
            'is_active'     => ['boolean']
        ];
    }
}

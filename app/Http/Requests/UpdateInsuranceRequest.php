<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
// use fully-qualified names for Rule to avoid namespace resolution issues

class UpdateInsuranceRequest extends FormRequest
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
        $insurance = $this->route('insurance');
        $id = $insurance instanceof \App\Models\Insurance ? $insurance->id : $insurance;

        return [
            #                                          unique: (table name)(column name)
            'insurance_code' => ['required', 'string', "unique:insurances,insurance_code", \Illuminate\Validation\Rule::unique('insurances', 'insurance_code')->ignore($id)],
            'name'          => ['required', 'string', "unique:insurances,name", \Illuminate\Validation\Rule::unique('insurances', 'name')->ignore($id)],
            'note'          => ['nullable', 'string', 'min:5', 'max:255'],
            'discount_percentage' => ['numeric', 'min:0', 'max:100'],
            'company_rate' => ['numeric', 'min:0', 'max:100'],
            'is_active'     => ['boolean']
        ];
    }
}

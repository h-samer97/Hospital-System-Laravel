<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGroupsRequest extends FormRequest
{
   
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string', 'min:2', 'max:30', 'unique:groups,name'],
            'notes' => ['nullable', 'max:255', 'string'],
            'subtotal' => ['decimal:2,10', 'required'],
            'discount' => ['decimal:2,10', 'nullable'],
            'total' => ['decimal:2,10', 'required'],
            'is_active' => ['required', 'boolean'],
            'items'                     => ['required', 'array', 'min:1'],
            'items.*.service_id'        => ['required', 'exists:services,id'],
            'items.*.quantity'          => ['required', 'integer', 'min:1'],
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'The group name is required.',
            'name.min' => 'The group name must be at least 2 characters.',
            'name.max' => 'The group name must not exceed 30 characters.',
            'name.unique' => 'The group name must be unique.',
            'notes.max' => 'The notes must not exceed 255 characters.',
            'subtotal.required' => 'The subtotal is required.',
            'subtotal.decimal' => 'The subtotal must be a decimal number with 2 decimal places.',
            'discount.decimal' => 'The discount must be a decimal number with 2 decimal places.',
            'total.required' => 'The total is required.',
            'total.decimal' => 'The total must be a decimal number with 2 decimal places.',
            'is_active.required' => 'The status is required.',
            'is_active.boolean' => 'The status must be a boolean value.',
        ];
    }

}

<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReceiptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
             'patient_id'  => ['required', 'exists:patients,id'],
            'debit'       => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'debit.min' => 'Amount must be greater than zero',
        ];
    }
}

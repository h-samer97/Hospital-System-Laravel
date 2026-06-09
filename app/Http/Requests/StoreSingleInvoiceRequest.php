<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSingleInvoiceRequest extends FormRequest
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
             'patient_id'     => ['required', 'exists:patients,id'],
            'doctor_id'      => ['required', 'exists:doctors,id'],
            'section_id'     => ['required', 'exists:sections,id'],
            'service_id'     => ['required', 'exists:services,id'],
            'price'          => ['required', 'numeric', 'min:0'],
            'discount_value' => ['nullable', 'numeric', 'min:0'],
            'tax_rate'       => ['nullable', 'numeric', 'min:0', 'max:100'],
            'type'           => ['required', 'in:cash,deferred'],
        ];
    }
}

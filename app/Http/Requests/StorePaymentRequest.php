<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow if the currently authenticated user (default guard) has the permission,
        // or fall back to the 'admin' guard if used elsewhere in the app.
        $user = $this->user() ?? $this->user('admins');
        return $user ? $user->can('payment.manage') : false;
    }

    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'exists:patients,id,is_active,1'],
            'amount'    => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'description' => ['nullable', 'string', 'max:255']
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('description')) {
            $this->merge([
                'description' => is_string($this->input('description')) ? trim($this->input('description')) : $this->input('description'),
            ]);
        }
    }
}

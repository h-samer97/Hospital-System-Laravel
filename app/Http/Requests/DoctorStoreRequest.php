<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'appointments' => 'required|array',
            'price' => 'required|numeric|min:0',
            'section_id' => 'required|exists:sections,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for photo
        ];
    }
}

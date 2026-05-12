<?php

namespace App\Http\Requests\dashboard;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSectionRequest extends FormRequest
{
   
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2',"unique:sections,name" , 'max:20'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The Name is Required',
            'name.unique' => 'The Name must be unique',
            'name.min' => 'The Name must be at least 2 characters',
            'name.max' => 'The Name must be less than 255 characters',
        ];
    }
}

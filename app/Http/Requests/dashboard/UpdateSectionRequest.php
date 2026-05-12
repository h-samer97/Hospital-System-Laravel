<?php

namespace App\Http\Requests\dashboard;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSectionRequest extends FormRequest
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

        $id = $this->route('sections');

        return [
            'name' => ['min:2', 'max:20', 'string', Rule::unique('sections')->ignore($id)],
        ];
    }

        public function messages(): array
    {
        return [
            'name.unique'   => 'This Name IS Exist',
            'name.required' => 'Name is Required',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:patients,email,'.$this->id,
            'password' => 'required|sometimes',
            'phone' => 'required|numeric|unique:patients,Phone,'.$this->id,
            'date_birth' => 'required|date|date_format:Y-m-d',
            'gender' => 'required|integer|in:1,2',
            'blood_group' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => trans('validation.required'),
            'email.unique' => trans('validation.unique'),
            'password.required' => trans('validation.required'),
            'password.sometimes' => trans('validation.sometimes'),
            'phone.required' => trans('validation.required'),
            'phone.unique' => trans('validation.unique'),
            'phone.numeric' => trans('validation.numeric'),
            'date_birth.required' => trans('validation.required'),
            'date_birth.date' => trans('validation.date'),
            'gender.required' => trans('validation.required'),
            'gender.integer' => trans('validation.integer'),
            'blood_group.required' => trans('validation.required'),
        ];
    }
}

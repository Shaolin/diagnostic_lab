<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTestRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
{
    return true;
}

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [

            'patient_id' => [
                'required',
                'integer',
                'exists:patients,id',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.test_type_id' => [
                'required',
                'integer',
                'exists:test_types,id',
            ],

            'items.*.price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'patient_id.required' => 'Please select a patient.',

            'items.required' => 'Please add at least one laboratory test.',

            'items.min' => 'Please add at least one laboratory test.',

            'items.*.price.min' => 'The test price cannot be negative.',
        ];
    }
}
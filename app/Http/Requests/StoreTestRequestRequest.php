<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTestRequestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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

            /*
            |--------------------------------------------------------------------------
            | Patient
            |--------------------------------------------------------------------------
            */

            'patient_id' => [
                'required',
                'integer',
                'exists:patients,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Test Items
            |--------------------------------------------------------------------------
            */

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

            /*
            |--------------------------------------------------------------------------
            | Remarks
            |--------------------------------------------------------------------------
            */

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [

            'patient_id.required' => 'Please select a patient.',

            'patient_id.exists' => 'The selected patient does not exist.',

            'items.required' => 'Please add at least one laboratory test.',

            'items.min' => 'Please add at least one laboratory test.',

            'items.*.test_type_id.required' => 'Please select a laboratory test.',

            'items.*.test_type_id.exists' => 'The selected laboratory test is invalid.',

            'items.*.price.required' => 'Please enter the test price.',

            'items.*.price.numeric' => 'The test price must be a number.',

            'items.*.price.min' => 'The test price cannot be negative.',
        ];
    }
}
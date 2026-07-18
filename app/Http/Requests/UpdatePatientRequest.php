<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'other_names' => ['nullable', 'string', 'max:255'],

            'gender' => ['required', 'in:Male,Female'],

            'date_of_birth' => ['nullable', 'date'],

            'phone' => ['required', 'string', 'max:20'],

            'email' => ['nullable', 'email', 'max:255'],

            'address' => ['nullable', 'string'],

            'blood_group' => [
                'nullable',
                'in:A+,A-,B+,B-,AB+,AB-,O+,O-'
            ],

            'genotype' => [
                'nullable',
                'in:AA,AS,AC,SS,SC,CC'
            ],

            'emergency_contact_name' => [
                'nullable',
                'string',
                'max:255'
            ],

            'emergency_contact_phone' => [
                'nullable',
                'string',
                'max:20'
            ],

            'notes' => ['nullable', 'string'],

            'is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
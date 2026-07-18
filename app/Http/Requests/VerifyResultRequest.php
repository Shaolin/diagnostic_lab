<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyResultRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
          public function authorize(): bool
{
    return true;
}

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('remarks')) {
            $this->merge([
                'remarks' => trim($this->remarks),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'remarks.string' => 'Verification remarks must be valid text.',
            'remarks.max' => 'Verification remarks may not exceed 1000 characters.',
        ];
    }

    /**
     * Get custom attribute names.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'remarks' => 'verification remarks',
        ];
    }
}
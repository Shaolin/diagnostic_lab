<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResultRequest extends FormRequest
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
            'pdf' => [
                'required',
                'file',
                'mimes:pdf',
                'max:10240', // 10MB
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'pdf.required' => 'Please upload a PDF result report.',
            'pdf.file' => 'The uploaded result must be a valid file.',
            'pdf.mimes' => 'Only PDF files are allowed.',
            'pdf.max' => 'The PDF file must not exceed 10 MB.',

            'remarks.string' => 'Remarks must be valid text.',
            'remarks.max' => 'Remarks may not exceed 1000 characters.',
        ];
    }

    /**
     * Custom attribute names.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'pdf' => 'result report',
        ];
    }
}
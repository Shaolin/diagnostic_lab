<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTestTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        $laboratoryId = auth()->user()->laboratory_id;

        return [

            'name' => [
                'required',
                'string',
                'max:255',

                Rule::unique('test_types')
                    ->ignore($this->route('test_type'))
                    ->where(fn ($query) => $query->where(
                        'laboratory_id',
                        $laboratoryId
                    )),
            ],

            'category' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'default_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'estimated_turnaround_hours' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }

    /**
     * Friendly attribute names.
     */
    public function attributes(): array
    {
        return [
            'default_price' => 'default price',
            'estimated_turnaround_hours' => 'estimated turnaround time',
        ];
    }
}
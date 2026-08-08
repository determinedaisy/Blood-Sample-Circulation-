<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewBloodSampleRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for accepting/rejecting a blood sample.
     */
    public function rules(): array
    {
        return [
            'decision' => [
                'required',
                'in:accepted,rejected',
            ],

            'quality_checks' => [
                'nullable',
                'array',
            ],

            'quality_checks.*' => [
                'boolean',
            ],

            'rejection_reason' => [
                'nullable',
                'required_if:decision,rejected',
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
            'decision.required' =>
                'Please accept or reject the blood sample.',

            'decision.in' =>
                'The decision must be accepted or rejected.',

            'rejection_reason.required_if' =>
                'A rejection reason is required when rejecting a blood sample.',
        ];
    }
}
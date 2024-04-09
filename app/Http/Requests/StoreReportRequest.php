<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'type.required_with' => 'The type field is required when normal report is created.',
            'shift_date.required_with' => 'The shift date field is required when normal report is created.',
            'shift_range.required_with' => 'The shift range field is required when normal report is created.',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'nullable|string|in:normal',
            'shift_range' => 'required_with:type,normal',
            'shift_date' => 'required_with:type,normal',
        ];
    }
}

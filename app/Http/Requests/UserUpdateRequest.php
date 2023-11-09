<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lastname' => 'string|nullable',
            'name' => 'string|nullable',
            'patronymic' => 'string|nullable',
            'birth_date' => 'date|nullable',
            'gender' => 'string|nullable',
            'birth_place' => 'string|nullable',
            'role' => 'int|nullable',
            'qualification_text' => 'string|nullable',
            'qualification_value' => 'int|nullable',
            'phone' => 'string|nullable',
            'email' => 'email|nullable',
            'number' => 'string|nullable',
            'series' => 'string|nullable',
            'when_issued' => 'date|nullable',
            'division_code' => 'string|nullable',
            'issued_by' => 'string|nullable',
            'inn' => 'string|nullable',
            'snils' => 'string|nullable',
            'reg_addr' => 'string|nullable',
            'fact_addr' => 'string|nullable',
        ];
    }
}

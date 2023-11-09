<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'lastname' => 'string|required',
            'name' => 'string|required',
            'patronymic' => 'string|required',
            'birth_date' => 'date|required',
            'gender' => 'string|required',
            'birth_place' => 'string|required',
            'role' => 'int|required',
            'qualification_text' => 'string|required',
            'qualification_value' => 'int|required',
            'phone' => 'string|required',
            'email' => 'email|required',
            'number' => 'string|required',
            'series' => 'string|required',
            'when_issued' => 'date|required',
            'division_code' => 'string|required',
            'issued_by' => 'string|required',
            'inn' => 'string|required',
            'snils' => 'string|required',
            'reg_addr' => 'string|required',
            'fact_addr' => 'string|required',
        ];
    }
}

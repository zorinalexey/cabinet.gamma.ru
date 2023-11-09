<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FundUpdateRequest extends FormRequest
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
            'name' => 'string|required',
            'qualification_value' => 'string|required',
            'access_users' => 'array|nullable',
            'status' => 'string|required',
            'omitted_min_percent' => 'string|required',
            'current_count_pif' => 'string|required',
            'last_count_pif' => 'string|nullable',
            'last_cost_one_pif' => 'string|nullable',
            'current_cost_one_pif' => 'string|nullable',
            'rules' => 'string|required',
            'policy' => 'string|required',
            'destiny' => 'string|required',
            'desc' => 'string|required',
        ];
    }
}

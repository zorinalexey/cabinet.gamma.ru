<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OmittedCreateRequest extends FormRequest
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
            'name' =>  'string|required',
            'fund_id' => 'integer|required',
            'total_date' => 'date|required',
            'end_date' => 'date|required',
            'start_date' => 'date|required',
            'votings' => 'array|required',
            'votings.*.type_transaction' => 'string|required',
            'votings.*.other_conditions' => 'string|nullable',
            'votings.*.decision_making' => 'int|nullable',
            'votings.*.decision_making_count' => 'int|nullable',
            'votings.*.parties_transaction' => 'string|required',
            'votings.*.subject_transaction' => 'string|required',
            'votings.*.cost_transaction' => 'string|required',
            'file' => 'file|nullable',
        ];
    }
}

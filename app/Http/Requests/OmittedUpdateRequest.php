<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OmittedUpdateRequest extends FormRequest
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
            'name' =>  'string|nullable',
            'file' => 'file|nullable',
            'fund_id' => 'integer|nullable',
            'total_date' => 'date|nullable',
            'end_date' => 'date|nullable',
            'start_date' => 'date|nullable',
            'votings' => 'array|required',
            'votings.*.id' => 'int|nullable',
            'votings.*.type_transaction' => 'string|required',
            'votings.*.decision_making' => 'int|nullable',
            'votings.*.decision_making_count' => 'int|nullable',
            'votings.*.other_conditions' => 'string|nullable',
            'votings.*.parties_transaction' => 'string|required',
            'votings.*.subject_transaction' => 'string|required',
            'votings.*.cost_transaction' => 'string|required',
        ];
    }
}

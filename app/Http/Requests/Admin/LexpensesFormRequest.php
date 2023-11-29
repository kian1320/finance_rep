<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LexpensesFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'date_issued' => [
                'required',
                'string',
                'max:200'
            ],

            'voucher' => [
                'required',
                'string',
                'max:200'
            ],

            'check' => [
                'required',
                'string',
                'max:200'
            ],

            'encashment' => [
                'nullable',
                'string',
                'max:200'
            ],

            'description' => [
                'required',
                'string',
                'max:200'
            ],

            'type_id' => [
                'required',
                'string',
                'max:200'
            ],

            'stype_id' => [
                'required',
                'string',
                'max:200'
            ],

            'amount' => [
                'required',
                'string',
                'max:200'
            ],



        ];
        return $rules;
    }
}

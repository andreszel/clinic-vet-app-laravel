<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddVisitStep1 extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => ['required', 'exists:customers,id', 'integer'],
            'pay_type_id' => ['required', 'exists:pay_types,id', 'integer'],
            'visit_date' => ['required', 'date_format:Y-m-d']
        ];
    }
}

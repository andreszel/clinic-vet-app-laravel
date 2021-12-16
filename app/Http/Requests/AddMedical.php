<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMedical extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            //'net_price_buy' => ['required_without:gross_price_buy', 'regex:/^\d*([\.\,]\d{2})?$/'],
            'vat_buy_id' => ['required', 'exists:vats,id'],
            //'gross_price_buy' => ['required_without:net_price_buy', 'regex:/^\d*([\.\,]\d{2})?$/'],
            //'net_price_sell' => ['required_without:gross_price_sell', 'regex:/^\d*([\.\,]\d{2})?$/'],
            'vat_buy_id' => ['required', 'exists:vats,id'],
            //'gross_price_sell' => ['required_without:net_price_sell', 'regex:/^\d*([\.\,]\d{2})?$/'],
            'unit_measure_id' => ['required', 'exists:unit_measures,id'],
            'active' => ['required']
        ];
    }
}

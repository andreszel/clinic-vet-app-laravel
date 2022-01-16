<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddAdditionalServices extends FormRequest
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
            'vat_id' => ['required', 'exists:vats,id'],
            'gross_price' => ['required_if:set_price_in_visit,0'],
            'nightly_gross_price' => ['required_if:set_price_in_visit,0'],
            'set_price_in_visit' => ['required'],
            'active' => ['required'],
            'description' => [],
            'drive_to_customer' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'gross_price.required_if' => 'Pole cena brutto jest wymagane, jeżeli cena nie jest wpisywana przy zamówieniu'
        ];
    }
}

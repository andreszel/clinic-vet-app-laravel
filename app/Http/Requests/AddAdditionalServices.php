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
            'gross_price' => ['required_without:set_price_in_visit', 'regex:/^\d*([\.\,]\d{2})?$/'],
            'set_price_in_visit' => ['required'],
            'active' => ['required'],
            'description' => []
        ];
    }
}

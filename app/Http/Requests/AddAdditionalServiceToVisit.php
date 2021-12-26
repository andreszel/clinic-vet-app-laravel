<?php

namespace App\Http\Requests;

use App\Models\AdditionalService;
use Illuminate\Foundation\Http\FormRequest;

class AddAdditionalServiceToVisit extends FormRequest
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
            'visit_id' => 'required|integer|exists:visits,id',
            'additional_service_id' => 'required|integer|exists:additional_services,id|unique:visit_additional_services',
            'quantity' => 'required|integer|min:1',
            //'vat_id' => 'required|integer|exists:vats,id',
            //'net_price' => 'required',
            //'gross_price' => 'required'
        ];
    }
}

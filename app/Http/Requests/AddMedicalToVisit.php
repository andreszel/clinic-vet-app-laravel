<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMedicalToVisit extends FormRequest
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
            'medical_id' => 'required|integer|exists:medicals,id',
            'quantity' => 'required|integer|min:1',
            //'vat_id' => 'required|integer|exists:vats,id',
            //'net_price' => 'required',
            //'gross_price' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'visit_id.exists' => 'Lek, który próbujesz dodać nie istnieje w naszej bazie',
            'visit_id.unique' => 'Ten lek jest już dodany do wizyty'
        ];
    }
}

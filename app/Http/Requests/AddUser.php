<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUser extends FormRequest
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
            'name' => ['required', 'required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'min:3'],
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'string', 'min:9'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'commission_services' => ['required', 'integer'],
            'commission_medicals' => ['required', 'integer'],
            'type_id' => ['required', 'integer'],
            'active' => ['required']
        ];
    }
}

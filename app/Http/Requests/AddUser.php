<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $edit_user = User::find($this->id);

        //'unique:users'
        $rule_validation = Rule::unique('users');
        if ($edit_user) {
            $rule_validation = Rule::unique('users')->ignore($edit_user->email, 'email');
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'min:3'],
            'phone' => ['required', 'regex:/^([0-9\s\-\+\(\)]*)$/', 'string', 'min:9'],
            'email' => [
                'required',
                'email',
                $rule_validation
            ],
            'commission_services' => ['required', 'integer'],
            'commission_medicals' => ['required', 'integer'],
            'type_id' => ['required', 'integer'],
            'active' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Pole imię jest wymagane',
            'name.string' => 'Imię musi być tekstem',
            'nam.max' => 'Makymalna ilość znaków to :max'
        ];
    }
}

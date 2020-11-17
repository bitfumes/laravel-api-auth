<?php

namespace Bitfumes\ApiAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        $rules = [
            'name'     => 'required|min:3|max:25',
            'email'    => 'required|unique:users|email',
            'password' => 'required|confirmed|min:8',
        ];

        $custom = config('api-auth.validations');
        $custom = gettype($custom) === 'array' ? $custom : $custom();
        return array_merge($rules, $custom);
    }
}

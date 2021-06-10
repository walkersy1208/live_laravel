<?php

namespace App\Http\Requests\Admin;

use App\Rules\adminLoginRules;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class adminLoginRequest extends FormRequest
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
            'name' => ['required', new adminLoginRules()],
            'password' => 'required|min:3',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '用户名不能为空',
            'password.required' => '密码不能为空',
            'password.min' => '密码不能少于3位',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw (new HttpResponseException(response()->json([
            'code' => '-1',
            'errors' => $validator->errors(),
        ], 200)));
    }
}

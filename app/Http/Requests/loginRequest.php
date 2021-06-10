<?php

namespace App\Http\Requests;

use App\Rules\Checklogin;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class loginRequest extends FormRequest
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
            'email' => ['required', 'email', new CheckLogin()],
            'password' => 'required|min:3',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => '邮件不能为空',
            'email.email' => '邮件格式不正确',
            'password.required' => '密码不能为空',
            'password.min' => '密码不能少于3位',
        ];
    }

    //For Api Request
    // public function failedValidation(Validator $validator)
    // {
    //     throw (new HttpResponseException(response()->json([
    //         'code' => '-1',
    //         'errors' => $validator->errors(),
    //     ], 200)));
    // }
}

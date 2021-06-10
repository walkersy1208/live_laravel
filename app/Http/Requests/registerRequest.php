<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class registerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return false;
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
            'email' => 'required|unique:blog_users|email',
            'password' => 'required|min:5|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => '邮件不能为空',
            'email.unique' => '邮箱已经有人注册了',
            'email.email' => '请输入正确的邮箱格式',
            'email.required' => '密码不能为空',
            'email.min' => '最少输入5位字符',
            'email.comfirmed' => '请确定两次输入的密码一致',
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

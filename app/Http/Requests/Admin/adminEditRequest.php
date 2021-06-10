<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class adminEditRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|min:3',
            //'password' => 'required|min:3|required_with:repassword|same:repassword',
            'password' => 'nullable|min:3|same:repassword',
            'repassword' => 'nullable|min:3',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '管理员名称不能为空',
            'password.min' => '管理员密码不能少于3位',
            'password.same' => '密码和确认密码必须一致',
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

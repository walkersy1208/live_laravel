<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class roleRequest extends FormRequest
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
            'name' => 'required|unique:roles',
            'desc' => 'required|min:5|max:30',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '角色名称不能为空',
            'name.unique' => '所添加的角色名称已经存在',
            'desc.required' => '描述不能为空',
            'desc.min' => '描述最少输入5位字符',
            'desc.max' => '描述最多输入30位字符',
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

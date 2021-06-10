<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class noticeRequest extends FormRequest
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
            'title' => 'required|min:5|max:10',
            'content' => 'required|min:5|max:30',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '消息名称不能为空',
            'title.min' => '消息名称最少输入5位字符',
            'title.max' => '消息名称最多输入10位字符',
            'content.required' => '消息内容不能为空',
            'content.min' => '描述最少输入5位字符',
            'content.max' => '描述最多输入30位字符',
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

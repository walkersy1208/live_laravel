<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class articleRequest extends FormRequest
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
            'title' => 'required|max:150',
            'content' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '标题不能为空',
            //'title.max' => '不能超过50个字符',
            'content.required' => '内容不能为空',
        ];
    }
}

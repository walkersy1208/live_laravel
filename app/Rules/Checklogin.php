<?php

namespace App\Rules;

use App\BlogUsers;
use Illuminate\Contracts\Validation\Rule;

class Checklogin implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //dd($value);
        $result = BlogUsers::where('email', $value)->first();

        return $result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '登录邮件不存在';
    }
}

<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\FormRequest;

class LoginRequest extends FormRequest
{
    protected $paramKey = 'user';
    protected $table    = 'users';

    public function rules()
    {
        return [
            'email'       => ['required', 'email'],
            'email'       => ['required'],
            'remember_me' => ['boolean'],
        ];
    }
}

<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\FormRequest;

class UserRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name'      => ['required', 'min:3', 'max:100'],
            'email'     => ['required', 'email', $this->uniqueWithParams('users', 'email', [
                'type' => User::class,
            ])],
            'phone'     => ['required'],
            'bio'       => ['min:3', 'max:65535'],
            'gender'    => ['min:1'],
            'birthdate' => ['date'],

            'thumbnail' => [
                'image',
                'mimes:jpg,jpeg,png',
                'max:2040',
                // 'dimensions:min_width=100,min_height=100,max_height=500,max_width=500',
            ],
        ];

        if ($this->isCreate()) {
            $rules['password'] = ['required', 'min:6'];
        }

        return $rules;
    }
}

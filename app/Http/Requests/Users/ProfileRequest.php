<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\FormRequest;
use App\Models\Users\Admin;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'name'      => ['min:3', 'max:100'],
            'email'     => ['email', $this->uniqueEmail()],
            'phone'     => ['required'],
            'bio'       => ['min:3', 'max:65535'],
            'gender'    => ['min:1'],
            'birthdate' => ['date'],
            'thumbnail' => [
                'image',
                'mimes:jpg,jpeg,png',
                'max:2040',
            ],
            'password'  => ['min:6'],
        ];

        return $rules;

    }

    protected function uniqueEmail()
    {
        $rules = Rule::unique('users', 'email')->where(function ($query) {
            $query->where('type', Admin::class);
        });

        if ($this->isUpdate()) {
            return $rules->ignore(auth()->user()->id);
        }

        return $rules;
    }
}

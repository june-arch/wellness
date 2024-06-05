<?php

namespace App\Http\Requests\Users;

use App\Models\Users\Admin;

class AdminRequest extends UserRequest
{
    protected $paramKey = 'admin';

    public function rules()
    {
        $rules = parent::rules();

        $rules['email'] = ['required', 'email', $this->uniqueWithParams('users', 'email', [
            'type' => Admin::class,
        ])];

        if (auth()->user()->company_id) {
            $rules['role_id'] = ['required', 'not_in:1', 'exists:roles,id'];
        } else {
            $rules['role_id'] = ['required', 'exists:roles,id'];
        }

        return $rules;
    }
}

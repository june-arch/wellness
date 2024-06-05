<?php

namespace App\Http\Requests\Companies;

use App\Http\Requests\FormRequest;

class CompanyRequest extends FormRequest
{
    protected $paramKey = 'company';

    public function rules()
    {
        $rules = [
            'name'        => ['required', 'min:3', 'max:100'],
            'description' => ['required', 'min:10'],
            'phone'       => ['required', 'min:5', 'max:100', 'unique:companies,phone'],
            'email'       => ['email', 'min:3', 'max:100', 'unique:companies,email'],
            'address'     => ['required', 'min:10', 'max:250'],
            'is_active'   => ['required', 'boolean'],
            'expires_at'  => ['required', 'date'],
            'thumbnail'   => [
                'image',
                'mimes:jpg,jpeg,png',
                'max:2040',
            ],
        ];

        if ($this->isCreate()) {
            $rules['admin_name']    = ['required', 'min:3', 'max:100'];
            $rules['admin_role_id'] = ['required', 'exists:roles,id'];
            $rules['admin_email']   = ['required', 'email', $this->uniqueWithParams('companies', 'email', [
                'type' => Admin::class,
            ])];
            $rules['admin_password']              = ['required', 'confirmed', 'min:6'];
            $rules['admin_password_confirmation'] = ['required'];
        }

        return $rules;
    }
}

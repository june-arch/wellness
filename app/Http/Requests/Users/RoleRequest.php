<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\FormRequest;

class RoleRequest extends FormRequest
{
    protected $paramKey = 'role';

    protected $table = 'roles';

    public function rules()
    {
        return [
            'name'        => ['required', 'min:5'],
            'description' => ['min:10'],
        ];
    }
}

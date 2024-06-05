<?php

namespace App\Http\Requests\Users;

use App\Models\Users\Member;

class MemberRequest extends UserRequest
{
    protected $paramKey = 'member';

    public function rules()
    {
        $rules = parent::rules();

        $rules['email'] = ['required', 'email', $this->uniqueWithParams('users', 'email', [
            'type' => Member::class,
        ])];

        if (!auth()->user()->company_id && $this->isCreate()) {
            $rules['company_id'] = ['required', 'exists:companies,id'];
        }

        return $rules;
    }
}

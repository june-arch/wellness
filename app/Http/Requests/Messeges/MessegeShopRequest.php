<?php

namespace App\Http\Requests\Messeges;

use App\Http\Requests\FormRequest;

class MessegeShopRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'content' => ['required', 'min:10'],
        ];

        if (!request()->user()) {
            $rules['name']  = ['required', 'min:3'];
            $rules['phone'] = ['required', 'min:8'];
            $rules['email'] = ['email'];
        }

        return $rules;
    }
}

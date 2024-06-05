<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\FormRequest;

class SubscribtionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'subscribtions'   => ['array'],
            'subscribtions.*' => ['exists:subscribtions,key'],
        ];
    }
}

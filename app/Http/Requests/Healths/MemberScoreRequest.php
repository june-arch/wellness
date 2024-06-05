<?php

namespace App\Http\Requests\Healths;

use App\Http\Requests\FormRequest;

class MemberScoreRequest extends FormRequest
{
    protected $paramKey = 'health';

    public function rules()
    {
        $rules = [
            'step' => ['required', 'min:0'],
            'distance' => ['required', 'min:0'],
            'calorie' => ['required', 'min:0'],
            'freq_time' => ['required', 'min:0'],
            'weight' => ['required', 'min:0'],
            'height' => ['required', 'min:0'],
            'water' => ['required', 'min:0'],
            'sleep' => ['required', 'min:0'],
            'blood_pressure' => ['required', 'min:0']
        ];

        return $rules;
    }
}

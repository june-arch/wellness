<?php

namespace App\Http\Requests\Healths;

use App\Http\Requests\FormRequest;

class MemberHealthRequest extends FormRequest
{
    protected $paramKey = 'health';

    public function rules()
    {
        $companyThresholds = auth()->user()->company->healthThresholds;

        $codes = $companyThresholds->pluck('code')->join(',');

        $rules = [
            'code' => ['required', "in:ALL,SLEEP_DURATION"],
        ];

        $request = request();

        if ($request->code === 'ALL') {
            $rules['data']                = ['required', 'array'];
            $rules['data.*.code']         = ['required', "in:{$codes}"];
            $rules['data.*.data']         = ['required', 'array'];
            $rules['data.*.data.*.time']  = ['required', 'string'];
            $rules['data.*.data.*.value'] = ['required', 'integer'];

        } else {
            $rules['data']                     = ['required'];
            $rules['data.start_time']          = ['required', 'date'];
            $rules['data.end_time']            = ['required', 'date'];
            $rules['data.deep_sleep_duration'] = ['required', 'integer'];
            $rules['data.rem_duration']        = ['required', 'integer'];
        }

        return $rules;
    }
}

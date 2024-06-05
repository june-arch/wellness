<?php

namespace App\Http\Requests\Companies;

use App\Http\Requests\FormRequest;
use App\Models\Companies\HealthThreshold;
use Illuminate\Validation\Rule;

class CompanyThresholdRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'target' => ['required', 'integer'],
            'ratio'  => ['required', 'numeric'],
        ];

        if ($this->isCreate()) {
            $rules['company_id'] = ['required', 'exists:companies,id'];
            $rules['code']       = [
                'required',
                Rule::in(collect(HealthThreshold::$THRESHOLDS)->pluck('code')),
                $this->uniqueWithParams('health_thresholds', 'code', [
                    'company_id' => request()->company_id,
                ]),
            ];
        }

        return $rules;
    }
}

<?php

namespace App\Http\Requests\Tasks;

use App\Http\Requests\FormRequest;

class TaskCategoryRequest extends FormRequest
{
    protected $paramKey = 'task_category';

    public function rules()
    {
        return [
            'name'        => ['required', 'max:160', $this->uniqueWithParams('task_categories', 'name', [
                'company_id' => auth()->user()->company_id,
            ])],
            'description' => ['required'],
        ];
    }
}

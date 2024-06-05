<?php

namespace App\Http\Requests\Tasks;

use App\Http\Requests\FormRequest;

class TaskRequest extends FormRequest
{
    protected $paramKey = 'task';

    public function rules()
    {
        return [
            'name'        => ['required', 'max:160'],
            'description' => ['required'],
            'category_id' => ['required', 'integer'],
            'tag_ids'     => ['array'],
            'company_id'  => ['exists:companies,id'],
            'thumbnail'   => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2040'],
            'media'       => ['required', 'mimes:mp4,mp3', 'max:99000'],
        ];
    }
}

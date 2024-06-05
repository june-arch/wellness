<?php

namespace App\Http\Requests;

class CommentRequest extends FormRequest
{
    protected $paramKey = 'comment';

    public function rules()
    {
        return [
            'name'      => ['required'],
            'content'   => ['required'],
            'rating'    => ['integer'],
            'parent_id' => ['required', 'exists:comments:id'],
        ];
    }
}

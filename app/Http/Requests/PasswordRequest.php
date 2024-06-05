<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordRequest extends FormRequest
{
    protected $paramKey = 'user';

    public function rules()
    {
        return [
            'password'              => ['required', 'confirmed'],
            'password_confirmation' => ['required'],
            'current_password'      => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
        ];
    }
}

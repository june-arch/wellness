<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ProfileRequest;
use App\Http\Resources\Companies\CompanyResource;
use App\Http\Resources\Users\UserResource;
use App\Models\Users\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AdminAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required', 'min:6', 'max: 20'],
            'remember_me' => [],
        ]);

        $credentials = [
            'email'     => $request->email,
            'password'  => $request->password,
            'type'      => Admin::class,
            'is_active' => true,
        ];

        if (!Auth::guard('admin')->attempt($credentials, $request->remember_me ?? false)) {
            return $this->error('Email or password is incorrect', 401);
        }

        $user = Admin::where('email', $request->email)->with('permissions')->firstOrFail();

        if ($user->company_id && !$user->company->status) {
            return $this->error('Your account is inactive', 401);
        }

        return $this->success(new UserResource($user), 'Success logged in');
    }

    public function resetPassword(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
        ? response()->json(__($status))
        : response()->json(['error' => ['email' => __($status)]], 442);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return $this->success('', 'Success logged out');
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->res(false);
        }

        return $this->success(new UserResource($user));
    }

    public function updateProfile(ProfileRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());

        if ($request->thumbnail) {
            $this->saveThumbnail($user);
        }

        return $this->success(new UserResource($user->refresh()));

    }

    public function company(Request $request)
    {
        $user = $request->user();

        return $this->success(new CompanyResource($user->company));
    }
}

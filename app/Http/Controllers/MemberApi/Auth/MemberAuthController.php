<?php

namespace App\Http\Controllers\MemberApi\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ProfileRequest;
use App\Http\Resources\Users\MemberResource;
use App\Models\Users\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class MemberAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required', 'min:6', 'max: 20'],
            'device_name' => ['required'],
        ]);

        return $this->attemp($request);
    }

    public function social(Request $request)
    {
        $request->validate([
            'token'       => ['required'],
            'provider'    => ['required', 'in:google,twitter,facebook'],
            'device_name' => ['required'],
        ]);

        $socialUser = Socialite::driver($request->provider)->userFromToken($request->token);

        return $this->attemp($request, $socialUser->email);
    }

    protected function attemp(Request $request, $email = '')
    {
        $user = Member::where('email', $email ? $email : $request->email)
            ->where('type', Member::class)->where('is_active', true)
            ->first();

        if (!$email && (!$user || !Hash::check($request->password, $user->password))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($user->company_id && !$user->company->status) {
            return $this->error('Your account is inactive', 401);
        }

        $tokens = $user->tokens;

        if ($tokens->count() > 4) {
            $user->tokens()->orderBy('created_at', 'asc')->take($tokens->count() - 4)->delete();
        }

        $token = $user->createToken($request->device_name ?? 'Unknonw Device');

        return $this->success(collect(new MemberResource($user))->put('accessToken', $token->plainTextToken));
    }

    public function resetPassword(Request $request)
    {
        $this->middleware('guest');

        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
        ? $this->success(__($status))
        : $this->error(['error' => ['email' => __($status)]], 400);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success('', 'Success logged out');
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return $this->res(false);
        }

        return $this->success(new MemberResource($user));
    }

    public function updateProfile(ProfileRequest $request)
    {
        $user = $request->user();

        $user->update($request->validated());

        if ($request->thumbnail) {
            $this->saveThumbnail($user);
        }

        return $this->success(new MemberResource($user->refresh()));

    }
}

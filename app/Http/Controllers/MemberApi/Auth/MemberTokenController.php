<?php

namespace App\Http\Controllers\MemberApi\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MemberTokenController extends Controller
{
    public function tokens(Request $request)
    {
        $tokens = $request->user()->tokens
            ->map(fn($item) => [
                'id'          => $item->id,
                'device_name' => $item->name,
                'is_current'  => $request->user()->currentAccessToken()->id === $item->id,
                'created_at'  => $item->created_at,
            ]);

        return $this->success($tokens ?? []);
    }

    public function removeToken(Request $request, string $id)
    {
        $request->user()->tokens()->where('id', $id)->delete();
        return $this->success('', 'Success removing token');
    }
}

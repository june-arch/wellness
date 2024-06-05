<?php

namespace App\Http\Controllers\Api\Users\Admins;

use App\Http\Collections\Collection;
use App\Http\Collections\LogableCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\Users\ProfileRequest;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\Request;

class AdminProfileController extends Controller
{
    public function index(Request $request)
    {
        return new UserResource($request->user());
    }

    public function update(ProfileRequest $request)
    {
        $request->user()->update($request->validated());

        $this->saveThumbnail($request->user());

        return $request->user()->only('id', 'name');
    }

    public function updatePassword(PasswordRequest $request)
    {
        $request->user()->update($request->validated());

        return $request->user()->only('id', 'name');
    }

    public function permissions(Request $request)
    {
        $this->table = 'permissions';

        $this->query = $request->user()->permissions()
            ->select('gate');

        $this->filter(['gate']);

        return new Collection($this->query);
    }

    public function logs(Request $request)
    {
        $this->table = 'logs';

        $this->query = $request->user()->logs()
            ->select('id', 'user_id', 'data', 'method', 'path', 'ip_address', 'logable_id', 'logable_type', 'created_at')
            ->with('logable');

        $this->filter(
            [
                'method',
                'path',
                'ip_address',
                'type',
                'created_at',
            ]
        );

        return new LogableCollection($this->query);
    }
}

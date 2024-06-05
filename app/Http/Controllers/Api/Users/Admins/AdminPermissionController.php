<?php

namespace App\Http\Controllers\Api\Users\Admins;

use App\Http\Collections\Collection;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
use App\Models\Users\Admin;
use App\Models\Users\Permission;
use Illuminate\Http\Request;

class AdminPermissionController extends Controller
{
    public function index(Admin $admin)
    {
        $this->query = $admin->permissions()
            ->select('gate');

        $this->filter(['gate']);

        return new Collection($this->query);
    }

    public function store(Request $request, Admin $admin)
    {
        if (request()->user()->id === $admin->id) {
            abort(403, "You can't update your own permission.");
        }

        $request->validate(['permissions' => ['array']]);

        $permissions = Permission::select('id')
            ->whereIn('gate', $request->permissions)
            ->get();

        $admin->permissions()->sync($permissions);

        return new UserResource($admin);
    }
}

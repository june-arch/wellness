<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Collections\Users\RoleCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\RoleRequest;
use App\Http\Resources\Users\RoleResource;
use App\Models\Users\Role;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function index()
    {
        if (Auth::user()->role_id == 1) {
            $query = Role::withCount('members');
        } else {
            $query = Role::where('id', '!=', 1)->withCount('members');
        }

        return $this->collection(new RoleCollection($query));
    }

    public function store(RoleRequest $request)
    {
        $role = Role::create($request->validated());
        return $this->created(new RoleResource($role));
    }

    public function show(Role $role)
    {
        return $this->success(new RoleResource($role));
    }

    public function update(RoleRequest $request, Role $role)
    {
        $role->update($request->except('password'));

        return $this->success(new RoleResource($role->fresh()));
    }

    public function destroy(Role $role)
    {
        return $this->success($role->delete());
    }
}

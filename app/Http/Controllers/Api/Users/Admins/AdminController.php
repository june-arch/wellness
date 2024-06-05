<?php

namespace App\Http\Controllers\Api\Users\Admins;

use App\Http\Collections\Users\UserCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\AdminRequest;
use App\Http\Resources\Users\UserResource;
use App\Models\Users\Admin;
use App\Repositories\AdminRepository;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminRepository::index($request);

        return $this->collection(new UserCollection($query));
    }

    public function store(AdminRequest $request)
    {
        $admin = Admin::create($request->validated());
        $this->saveThumbnail($admin);

        return $this->created(new UserResource($admin), 201);
    }

    public function show(Admin $admin)
    {
        $this->validateCompanyId($admin);

        return $this->success(new UserResource($admin));
    }

    public function update(AdminRequest $request, Admin $admin)
    {
        $this->validateCompanyId($admin);

        if (request()->user()->id === $admin->id) {
            abort(403, __("You can't update your own account."));
        }

        $admin->update($request->except('password'));

        $this->saveThumbnail($admin);

        return $this->success(new UserResource($admin->fresh()));
    }

    public function destroy(Admin $admin)
    {
        $this->validateCompanyId($admin);

        if (request()->user()->id === $admin->id) {
            abort(403, "You can't delete your account.");
        }

        return $this->success($admin->delete());
    }

    protected function validateCompanyId(Admin $admin)
    {
        $companyId = auth()->user()->company_id;

        if ($companyId && $companyId !== $admin->company_id) {
            abort('404');
        }
    }
}

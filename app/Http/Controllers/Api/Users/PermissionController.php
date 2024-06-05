<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Collections\Collection;
use App\Http\Controllers\Controller;
use App\Models\Users\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $table = 'permissions';

    public function index(Request $request)
    {
        if ($request->get_all === true) {
            return $this->res(Permission::orderBy('gate')->get());
        }

        $this->query = Permission::select('gate');

        $this->filter(['gate']);

        return new Collection($this->query);
    }
}

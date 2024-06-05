<?php

namespace App\Repositories;

use App\Models\Users\Admin;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class AdminRepository extends Repository
{
    public static function index(Request $request)
    {
        $query = Admin::with(['thumbnail', 'role:id,name', 'role.thumbnail', 'company:id,name']);

        if ($companyId = auth()->user()->company_id) {
            $query->where('company_id', $companyId);
        }

        if (!$request->filters) {
            $query->orderBy('name', 'asc');
        }

        $allowedData = [
            'filters'       => ['id', 'name', 'phone', 'email', 'role_id', 'role.name', 'company.name'],
            'globalFilters' => ['id', 'name', 'email', 'phone', 'role_id'],
            'orders'        => ['id', 'name', 'email', 'phone', 'role_id', 'created_at', 'updated_at'],
        ];

        self::parsedRequestFilter(
            $request,
            $query,
            $allowedData
        );

        return $query;
    }
}

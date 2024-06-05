<?php

namespace App\Repositories;

use App\Models\Users\Member;
use Illuminate\Http\Request;

class MemberRepository extends Repository
{

    public static function index(Request $request)
    {

        $query = Member::with(['thumbnail', 'company:id,name', 'supervisor:id,name', 'supervisor.thumbnail']);

        if ($companyId = auth()->user()->company_id) {
            $query->where('company_id', $companyId);
        }

        if (!$request->filters) {
            $query->orderBy('name', 'asc');
        }

        $allowedData = [
            'filters'       => ['id', 'name', 'phone', 'email', 'company.name', 'supervisor.name'],
            'globalFilters' => ['id', 'name', 'email', 'phone'],
            'orders'        => ['id', 'name', 'email', 'phone', 'created_at', 'updated_at'],
        ];

        self::parsedRequestFilter(
            $request,
            $query,
            $allowedData
        );

        return $query;
    }
}

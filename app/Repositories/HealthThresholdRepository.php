<?php

namespace App\Repositories;

use App\Models\Companies\HealthThreshold;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class HealthThresholdRepository extends Repository
{
    public static function index(Request $request)
    {
        $query = HealthThreshold::with('company:id,name');

        if ($companyId = auth()->user()->company_id) {
            $query->where('company_id', $companyId);
        }

        if (!$request->filters) {
            $query->orderBy('company_id', 'asc');
            $query->orderBy('code', 'asc');
        }

        $allowedData = [
            'filters'       => ['code', 'ratio', 'name', 'target', 'company_id', 'company.name'],
            'globalFilters' => ['code', 'ratio', 'name', 'target'],
            'orders'        => ['code', 'ratio', 'name', 'target'],
        ];

        self::parsedRequestFilter(
            $request,
            $query,
            $allowedData
        );

        return $query;
    }
}

<?php

namespace App\Repositories;

use App\Http\Requests\Companies\CompanyRequest;
use App\Models\Companies\Company;
use App\Models\Companies\HealthThreshold;
use App\Models\Users\Admin;
use App\Repositories\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyRepository extends Repository
{
    protected static $RETRY_IF_FAIL = 3;

    public static function index(Request $request)
    {
        $query = Company::with('thumbnail')->withCount('members');

        if (!$request->filters) {
            $query->orderBy('name', 'asc');
        }

        $allowedData = [
            'filters'       => ['id', 'name', 'phone', 'email', 'address'],
            'globalFilters' => ['id', 'name', 'email', 'phone', 'address'],
            'orders'        => ['id', 'name', 'email', 'phone', 'created_at', 'updated_at'],
        ];

        self::parsedRequestFilter(
            $request,
            $query,
            $allowedData
        );

        return $query;
    }

    public static function create(CompanyRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $company = Company::create($request->validated());

            Admin::create([
                'name'              => $request->admin_name,
                'email'             => $request->admin_email,
                'password'          => $request->admin_password,
                'role_id'           => $request->admin_role_id,
                'company_id'        => $company->id,
                'birthdate'         => now(),
                'email_verified_at' => now(),
            ]);

            $thresholds = self::defaultThresholds($company);

            HealthThreshold::insert($thresholds->toArray());

            return $company->fresh();
        }, self::$RETRY_IF_FAIL);
    }

    public static function defaultThresholds(Company $company)
    {
        return collect(HealthThreshold::$THRESHOLDS)
            ->filter(function ($item) {
                $selectedThreshold = ['SLEEP_DURATION', 'HEART_RATE', 'STRESS_LEVEL', 'MASTER_POINT', 'ACTIVITY_POINT'];

                return in_array($item['code'], $selectedThreshold);
            })
            ->map(function ($item) use ($company) {
                $item['company_id'] = $company->id;
                return $item;
            });

    }
}

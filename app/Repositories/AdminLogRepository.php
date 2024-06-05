<?php

namespace App\Repositories;

use App\Models\Users\Log;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class AdminLogRepository extends Repository
{
    public static function index(Request $request)
    {
        $query = Log::with(['admin:id,name']);

        if ($companyId = auth()->user()->company_id) {
            $query->whereHas('admin', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        }

        if (!$request->filters) {
            $query->orderBy('created_at', 'desc');
        }

        $allowedData = [
            'filters'       => ['method', 'path', 'ip_address', 'type', 'created_at', 'user.id', 'admin.name'],
            'globalFilters' => ['method', 'path', 'ip_address'],
            'orders'        => ['method', 'path', 'ip_address', 'type', 'created_at'],
        ];

        self::parsedRequestFilter(
            $request,
            $query,
            $allowedData
        );

        return $query;
    }
}

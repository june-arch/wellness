<?php

namespace App\Http\Controllers\Api\Reports;

use App\Models\Healts\Health;
use App\Models\Users\Admin;
use App\Models\Users\Member;
use Illuminate\Http\Request;

class DashboardReportController extends ReportController
{
    public function index(Request $request)
    {
        $totalMembers = Member::count();
        $fitnessDate = Health::latest('date')->first()->date;

        $fitness = Health::where('date', $fitnessDate)
            ->selectRaw(implode(',', [
                "COUNT(IF(fitness = 'fit', fitness, NULL)) as fit ",
                "COUNT(IF(fitness = 'temporary unfit', fitness, NULL)) as temporary_unfit",
                "COUNT(IF(fitness = 'unfit', fitness, NULL)) as unfit",
            ]))
            ->first();

        $tenDaysData = Health::where('date', '>', now()->subYear(2)->format('Y-m-d'))
            ->selectRaw(implode(',', [
                'date',
                "COUNT(IF(fitness = 'fit', fitness, NULL)) as fit ",
                "COUNT(IF(fitness = 'temporary unfit', fitness, NULL)) as temporary_unfit",
                "COUNT(IF(fitness = 'unfit', fitness, NULL)) as unfit",
            ]))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $masters = Admin::where('role_id', 3)->select('id', 'name', 'email', 'phone')->latest('created_at')->limit(10)->get();
        $members = Member::latest('created_at')->select('id', 'name', 'email', 'phone')->limit(10)->get();

        return $this->res([
            'empoyee' => [
                'total' => $totalMembers,
                'unfit' => $fitness->unfit,
                'temporary_unfit' => $fitness->temporary_unfit,
                'fit' => $fitness->fit,
            ],
            'fitness' => $tenDaysData,
            'fitnessDate' => $fitnessDate,
            'latest_registered_masters' => $masters,
            'latest_registered_members' => $members,
        ]);
    }
    public function detailFitness(Request $request)
    {
        $fitnessDate = Health::select('users.name', 'healths.*')
            ->join('users', 'healths.user_id', '=', 'users.id')
            ->where('date', '=', $request->date)
            ->where('fitness', '=', $request->fitness)
            ->get();
        return $this->res($fitnessDate);
    }
    public function performance(Request $request)
    {
        $fitnessDate = Health::select('users.name', 'users.email', 'users.birthdate', 'healths.point as bmi_point', 'healths.master_point as score')
            ->join('users', 'healths.user_id', '=', 'users.id')
            ->where('date', '=', $request->date)
            ->get();
        return $this->res($fitnessDate);
    }
}

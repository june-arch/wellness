<?php

namespace App\Http\Controllers\Api\Companies;

use App\Http\Collections\Companies\HealthThresholdCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Companies\CompanyThresholdRequest;
use App\Http\Resources\Companies\HealthThresholdResource;
use App\Models\Companies\HealthThreshold;
use App\Repositories\HealthThresholdRepository;
use Illuminate\Http\Request;

class HealthThresholdController extends Controller
{
    public function index(Request $request)
    {
        $data = HealthThresholdRepository::index($request);
        return $this->collection(new HealthThresholdCollection($data));
    }

    public function store(CompanyThresholdRequest $request)
    {
        $healthThreshold = HealthThreshold::create(array_merge($request->validated(), [
            'company_id' => $request->user()->company->id ?? $request->company_id,
            'name'       => collect(HealthThreshold::$THRESHOLDS)->firstWhere('code', $request->code)['name'],
        ]));

        return $this->created(new HealthThresholdResource($healthThreshold));
    }

    public function show(HealthThreshold $healthThreshold)
    {
        $this->validateCompanyId($healthThreshold);
        $healthThreshold->company = $healthThreshold->company;
        return $this->success(new HealthThresholdResource($healthThreshold));
    }

    public function update(CompanyThresholdRequest $request, HealthThreshold $healthThreshold)
    {
        $this->validateCompanyId($healthThreshold);

        $healthThreshold->update($request->validated());
        $healthThreshold->refresh();
        return $this->success(new HealthThresholdResource($healthThreshold), 'Success update data');
    }

    public function typeList()
    {
        $data = collect(HealthThreshold::$THRESHOLDS)->map(function ($item) {
            return [
                'code' => $item['code'],
                'name' => $item['name'],
            ];
        });

        return $this->success($data);
    }

    protected function validateCompanyId(HealthThreshold $healthThreshold)
    {
        $companyId = auth()->user()->company_id;

        if ($companyId && $healthThreshold->company_id && $companyId !== $healthThreshold->company_id) {
            abort('404');
        }
    }
}

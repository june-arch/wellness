<?php

namespace App\Http\Controllers\Api\Companies;

use App\Http\Collections\Companies\CompanyCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Companies\CompanyRequest;
use App\Http\Resources\Companies\CompanyResource;
use App\Models\Companies\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = CompanyRepository::index($request);

        return $this->collection(new CompanyCollection($query), 'Success get company list');
    }

    public function store(CompanyRequest $request)
    {
        $company = CompanyRepository::create($request);
        $this->saveThumbnail($company);
        return $this->created(new CompanyResource($company->fresh()));
    }

    public function show(Company $company)
    {
        return $this->success(new CompanyResource($company));
    }

    public function update(CompanyRequest $request, Company $company)
    {
        $company->update($request->validated());
        $this->saveThumbnail($company);
        return $this->success(new CompanyResource($company->fresh()));
    }
}

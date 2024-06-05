<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Collections\Healths\HealthUserCollection;
use App\Http\Collections\Users\MemberCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\MemberRequest;
use App\Http\Resources\Users\MemberResource;
use App\Models\Healts\Health;
use App\Models\Users\Member;
use App\Repositories\MemberRepository;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = MemberRepository::index($request);

        return $this->collection(new MemberCollection($query));
    }

    public function store(MemberRequest $request)
    {
        return $this->created(Member::create($request->validated()));
    }

    public function show(Member $member)
    {
        $this->validateCompanyId($member);
        return $this->success(new MemberResource($member));
    }

    public function update(MemberRequest $request, Member $member)
    {
        $this->validateCompanyId($member);

        return $this->success($member->update($request->validated()));
    }

    public function healths(Request $request, Member $member)
    {
        $this->validateCompanyId($member);

        $query = $member->healths();

        $sort     = in_array($request->sort, ['asc', 'desc']) ? $request->sort : 'desc';
        $order_by = in_array($request->order_by, (new Health)->fillable) ? $request->order_by : 'date';

        $query->orderBy($order_by, $sort);

        return new HealthUserCollection($query);
    }

    public function destroy(Member $member)
    {
        $member->healths()->delete();
        $member->addresses()->delete();
        $member->logs()->delete();
        $member->permissions()->delete();
        $member->subscribtions()->delete();
        $member->passwordReset()->delete();
        $member->files()->delete();
        return $this->res($member->delete());
    }

    protected function validateCompanyId(Member $member)
    {
        $companyId = auth()->user()->company_id;

        if ($companyId && $companyId !== $member->company_id) {
            abort('404');
        }
    }
}

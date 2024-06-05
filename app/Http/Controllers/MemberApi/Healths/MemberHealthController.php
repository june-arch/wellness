<?php
namespace App\Http\Controllers\MemberApi\Healths;

use App\Http\Collections\Healths\HealthCollection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Healths\MemberHealthRequest;
use App\Http\Requests\Healths\MemberScoreRequest;
use App\Repositories\Healths\HealthRepository;
use Illuminate\Http\Request;

class MemberHealthController extends Controller
{
    public function index(Request $request)
    {
        $query = HealthRepository::index($request);
        return $this->collection(new HealthCollection($query));
    }

    public function today(Request $request)
    {
        $item = $request->user()->healths()->latest('date')->first();

        $item = collect($item)->only('date', 'fitness')
            ->merge($item->pointData)
            ->put(
                'heart_rate',
                !isset($item['heart_rate']) || $item['heart_rate'] == 0 ? [] : $item['heart_rate']
            );

        return $this->success($item);
    }

    public function store(MemberHealthRequest $request)
    {
        $data = HealthRepository::updateHealth($request);
        return $this->success($data, 'Success update health data');
    }
    public function score(MemberScoreRequest $request)
    {
        $data = HealthRepository::calculateScore($request);
        return $this->success($data, 'Success update score');
    }

    public function healthCodes(Request $request)
    {
        $codes = $request->user()->company->healthThresholds->map(fn($item) => collect($item)->only('code', 'name'));
        return $this->success($codes);
    }
}

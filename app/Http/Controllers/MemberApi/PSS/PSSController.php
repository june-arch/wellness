<?php

namespace App\Http\Controllers\MemberApi\PSS;

use App\Http\Controllers\Controller;
use App\Models\Companies\HealthThreshold;
use App\Models\Healts\Health;
use Illuminate\Http\Request;

class PSSController extends Controller
{
    public function questions()
    {
        $pss_question = HealthThreshold::$PSS;
        return $this->success($pss_question);
    }

    public function answers()
    {
        $pss_answer = HealthThreshold::$PSS_Answer;
        return $this->success($pss_answer);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'total_point' => ['integer', 'min:0', 'max:40'],
        ]);

        $member = $request->user();

        Health::updateOrCreate(
            [
                'point'      => ceil($request->total_point / 40 * 100),
                'company_id' => $member->company_id,
                'user_id'    => $member->id,
            ],
            [
                'date' => now(),
            ]
        );

        return $this->success('', 'Success submit data');
    }
}

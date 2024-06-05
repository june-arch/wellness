<?php

namespace App\Repositories\Healths;

use App\Models\Healts\Health;
use App\Models\Users\Member;
use App\Repositories\Repository;
use Illuminate\Http\Request;

class HealthRepository extends Repository
{
    public static function index(Request $request)
    {
        $query = Health::where('user_id', $request->user()->id);

        if (!$request->filters) {
            $query->orderBy('date', 'desc');
        }

        $allowedData = [
            'filters' => ['id', 'date'],
            'globalFilters' => ['is_complete', 'date'],
            'orders' => ['is_compelete', 'date'],
        ];

        self::parsedRequestFilter(
            $request,
            $query,
            $allowedData
        );

        return $query;
    }

    public static function updateHealth(Request $request)
    {
        $user = $request->user();

        $key = strtolower($request->code);
        $value = $request->data;

        $health = Health::firstOrCreate([
            'user_id' => $user->id,
            'company_id' => $user->company_id,
            'date' => now()->format('Y-m-d'),
        ]);

        $data = collect([]);
        $healthId = $health->id;
        $heart_rate = Health::find($healthId)->heart_rate;
        if ($request->code === 'ALL') {
            foreach ($request->data as $val) {
                if ($val['code'] == 'HEART_RATE') {
                    # code...
                    $heart_rate[] = $val['data'][0];
                    $data->put(strtolower($val['code']), $heart_rate);
                } else {
                    $data->put(strtolower($val['code']), $val['data']);
                }
            }
        } else {
            $data->put($key, $value);
        }

        $health->update($data->toArray());

        return $data;
    }

    public static function generateDailyData()
    {
        $members = Member::where('is_active', true)->whereHas('company', function ($q) {
            $q->where('is_active', true)->where('expires_at', '>=', now());
        })->get();

        Health::insert(
            $members->map(fn($member) => [
                'date' => now(),
                'company_id' => $member->company_id,
                'user_id' => $member->id,
            ])->toArray()
        );
    }
    public static function calculateScore(Request $request)
    {
        // Start Calculate Score Track

        $step = $request->step;
        if ($step < 10000) {
            $step_score = 12.5;
        } else {
            $step_score = 25;
        }

        $distance = $request->distance;
        if ($distance < 7620) {
            $distance_score = 12.5;
        } else {
            $distance_score = 25;
        }

        $calorie = $request->calorie;
        if ($calorie < 400) {
            $calorie_score = 12.5;
        } else {
            $calorie_score = 25;
        }

        $freq_time = $request->freq_time;
        if ($freq_time < 600) {
            $freq_score = 12.5;
        } else {
            $freq_score = 25;
        }

        $track_score = ($step_score + $distance_score + $calorie_score + $freq_score) * 40 / 100;
        // End Score Track

        // Start Calculate BMI Score
        $bmi = Health::bmi_calculate($request->weight, $request->height);
        // $bmi = $request->weight / (($request->height / 100) * ($request->height / 100));
        if ($bmi < 60) {
            $bmi_score = 15;
        } else if ($bmi < 80) {
            $bmi_score = 15;
        } else if ($bmi < 120) {
            $bmi_score = 30;
        } else if ($bmi < 140) {
            $bmi_score = 15;
        } else {
            $bmi_score = 15;
        }
        // End Score Track

        // Start Calculate Score Water
        $water = $request->water;
        if ($water < 8) {
            $water_score = 2.5;
        } else {
            $water_score = 5;
        }
        // End Score Track

        // Start Calculate Sleep Track
        $sleep = $request->sleep;
        if ($sleep > 6 && $sleep < 8) {
            $sleep_score = 10;
        } else {
            $sleep_score = 5;
        }

        // End Score Track

        // Start Calculate Score Track
        $blood_pressure = $request->blood_pressure;
        if ($blood_pressure >= 110 && $blood_pressure <= 120) {
            $blood_score = 10;
        } else {
            $blood_score = 5;
        }
        // End Score Track

        $final_score = round($blood_score + $sleep_score + $water_score + $bmi_score + $track_score);

        // update fit

        $user = $request->user();
        $fitness = Health::fitScore($final_score);
        $health = Health::firstOrCreate([
            'user_id' => $user->id,
            'company_id' => $user->company_id,
            'date' => now()->format('Y-m-d'),
        ]);

        $data = collect([]);

        $data->put('fitness', $fitness);
        $data->put('master_point', $final_score);
        $data->put('bmi', $bmi);
        $data->put('point', $bmi);

        $health->update($data->toArray());
        $return_data = [
            'master_point' => $health->master_point,
            'fitness' => $health->fitness,
            'date' => $health->date,
        ];
        return $return_data;
    }

}

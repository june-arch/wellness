<?php

namespace App\Models\Healts;

use App\Models\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Users\Member;
use Illuminate\Support\Carbon;

class Health extends Model
{
    use BelongsToCompany;

    public $fillable = [
        'company_id',
        'user_id',
        'step',
        'workout_duration',
        'height',
        'weight',
        'waist_size',
        'hydration',
        'sleep_duration',
        'systolic',
        'diastolic',
        'blood_glucose',
        'cholesterol',
        'heart_rate',
        'smooke',
        'stress_level',
        'master_point',
        'point',
        'fitness',
        'bmi',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
        'heart_rate' => 'array',
        'stress_level' => 'array',
        'sleep_duration' => 'array',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'user_id');
    }

    protected function thresholds($code = '')
    {
        $threshold = $this->company->healthThresholds ?? collect([]);

        if ($code) {
            return $threshold->firstWhere('code', $code) ?? [];
        }

        return $threshold;
    }

    public function stepPoint()
    {
        $threshold = $this->thresholds('STEP');

        if (!$threshold) {
            return;
        }

        $ratio = $this->step / $threshold->target * 100;

        if ($ratio < 50) {
            $status = 'DANGER';
        } else if ($ratio < 80) {
            $status = 'WARNING';
        } else {
            $status = 'GOOD';
        }

        return [
            'value' => $this->step,
            'target' => $threshold->target,
            'ratio' => $ratio,
            'status' => $status,
            'point' => min($ratio, 100) * $threshold->ratio / 100,
        ];
    }

    public function workoutDurationPoint()
    {
        $threshold = $this->thresholds('WORKOUT_DURATION');

        if (!$threshold) {
            return;
        }

        $ratio = $this->workout_duration / $threshold->target * 100;

        if ($ratio < 50) {
            $status = 'DANGER';
        } else if ($ratio < 80) {
            $status = 'WARNING';
        } else {
            $status = 'GOOD';
        }

        return [
            'value' => $this->workout_duration,
            'target' => $threshold->target,
            'ratio' => $ratio,
            'status' => $status,
            'point' => min($ratio, 100) * $threshold->ratio / 100,
        ];
    }

    public function getBmiAttribute()
    {
        return $this->weight / (($this->height / 100) ^ 2);
    }

    public function bmiPoint()
    {
        $threshold = $this->thresholds('BMI');

        if (!$threshold) {
            return;
        }

        $bmi = $this->bmi;

        if ($bmi < 13) {
            $ratio = 0;
            $status = 'DANGER';
        } elseif ($bmi < 18.5) {
            $ratio = 50;
            $status = 'WARNING';
        } elseif ($bmi < 22.9) {
            $ratio = 100;
            $status = 'GOOD';
        } elseif ($bmi < 29.9) {
            $ratio = 50;
            $status = 'WARNING';
        } else {
            $ratio = -100;
            $status = 'DANGER';
        }

        return [
            'height' => $this->height,
            'weight' => $this->weight,
            'bmi' => $this->bmi,
            'ratio' => $ratio,
            'status' => $status,
            'point' => min($ratio, 100) * $threshold->ratio / 100,
        ];
    }

    public function waistSizePoint()
    {
        $threshold = $this->thresholds($this->member->gender === 'male' ? 'MEN_WAIST_SIZE' : 'WOMEN_WAIST_SIZE');

        if (!$threshold) {
            return;
        }

        $ratio = $this->waist_size / $threshold->target * 100;

        if ($ratio < 50) {
            $point = 0;
            $status = 'DANGER';
        } else if ($ratio < 80) {
            $point = 50;
            $status = 'WARNING';
        } else if ($ratio < 120) {
            $point = 100;
            $status = 'GOOD';
        } else if ($ratio < 140) {
            $point = 50;
            $status = 'WARNING';
        } else {
            $point = 0;
            $status = 'DANGER';
        }

        return [
            'value' => $this->waist_size,
            'target' => $threshold->target,
            'ratio' => $ratio,
            'status' => $status,
            'point' => $point * $threshold->ratio / 100,
        ];
    }

    public function hydrationPoint()
    {
        $threshold = $this->thresholds('HYDRATION');

        if (!$threshold) {
            return;
        }

        $ratio = $this->hydration / $threshold->target * 100;

        if ($ratio < 50) {
            $status = 'DANGER';
        } else if ($ratio < 80) {
            $status = 'WARNING';
        } else {
            $status = 'GOOD';
        }

        return [
            'value' => $this->hydration,
            'target' => $threshold->target,
            'ratio' => $ratio,
            'status' => $status,
            'point' => min($ratio, 100) * $threshold->ratio / 100,
        ];
    }

    public function sleepPoint()
    {
        $threshold = $this->thresholds('SLEEP_DURATION');

        if (!$threshold) {
            return;
        }

        $value = 0;

        if ($this->sleep_duration && gettype($this->sleep_duration) == 'array') {
            $startTime = Carbon::create($this->sleep_duration['start_time']);
            $endTime = Carbon::create($this->sleep_duration['end_time']);
            $value = round($startTime->diffInHours($endTime), 2);
        } else {
            $value = $this->sleep_duration;
        }

        $ratio = min($value, $threshold->target) / $threshold->target * 100;

        if ($ratio < 50) {
            $status = 'DANGER';
        } else if ($ratio < 80) {
            $status = 'WARNING';
        } else {
            $status = 'GOOD';
        }

        return [
            'history' => $this->sleep_duration,
            'value' => $value,
            'target' => $threshold->target,
            'ratio' => $ratio,
            'status' => $status,
            'point' => min($ratio, 100) * $threshold->ratio / 100,
        ];
    }

    public function systolicPoint()
    {
        $threshold = $this->thresholds('SYSTOLIC');

        if (!$threshold) {
            return;
        }

        if ($this->systolic < 70) {
            $point = -100;
            $status = 'DANGER';
        } elseif ($this->systolic < 90) {
            $point = 0;
            $status = 'WARNING';
        } elseif ($this->systolic < 125) {
            $point = 100;
            $status = 'GOOD';
        } elseif ($this->systolic < 140) {
            $point = 0;
            $status = 'WARNING';
        } else {
            $point = -100;
            $status = 'DANGER';
        }

        return [
            'value' => $this->systolic,
            'target' => '90 - 120',
            'status' => $status,
            'point' => $point * $threshold->ratio / 100,
        ];
    }

    public function diastolicPoint()
    {
        $threshold = $this->thresholds('DIASTOLIC');

        if (!$threshold) {
            return;
        }

        $point = 0;

        if ($this->diastolic < 60) {
            $point = 0;
            $status = 'DANGER';
        } elseif ($this->diastolic < 70) {
            $point = 50;
            $status = 'WARNING';
        } elseif ($this->diastolic < 80) {
            $point = 100;
            $status = 'GOOD';
        } elseif ($this->diastolic < 90) {
            $point = 0;
            $status = 'WARNING';
        } else {
            $point = -100;
            $status = 'DANGER';
        }

        return [
            'value' => $this->diastolic,
            'target' => '70 - 80',
            'status' => $status,
            'point' => $point * $threshold->ratio / 100,
        ];
    }

    public function blodGlucosePoint()
    {
        $threshold = $this->thresholds('BLOOD_SUGAR');

        if (!$threshold) {
            return;
        }

        $ratio = $this->blood_glucose / $threshold->target * 100;

        if ($ratio < 60) {
            $point = 0;
            $status = 'DANGER';
        } else if ($ratio < 80) {
            $point = 50;
            $status = 'WARNING';
        } else if ($ratio < 120) {
            $point = 100;
            $status = 'GOOD';
        } else if ($ratio < 140) {
            $point = 50;
            $status = 'WARNING';
        } else {
            $point = 0;
            $status = 'DANGER';
        }

        return [
            'value' => $this->blood_glucose,
            'target' => $threshold->target,
            'ratio' => $ratio,
            'status' => $status,
            'point' => $point * $threshold->ratio / 100,
        ];
    }

    public function cholesterolPoint()
    {
        $threshold = $this->thresholds('CHOLESTEROL');
        if (!$threshold) {
            return;
        }

        $ratio = $this->cholesterol / $threshold->target * 100;

        if ($ratio < 60) {
            $point = 0;
            $status = 'DANGER';
        } else if ($ratio < 80) {
            $point = 50;
            $status = 'WARNING';
        } else if ($ratio < 120) {
            $point = 100;
            $status = 'GOOD';
        } else if ($ratio < 140) {
            $point = 50;
            $status = 'WARNING';
        } else {
            $point = 0;
            $status = 'DANGER';
        }

        return [
            'value' => $this->cholesterol,
            'target' => $threshold->target,
            'ratio' => $ratio,
            'status' => $status,
            'point' => $point * $threshold->ratio / 100,
        ];
    }

    public function stressLevelPoint()
    {
        $threshold = $this->thresholds('STRESS_LEVEL');

        if (!$threshold) {
            return;
        }

        $value = collect($this->stress_level)->avg('value') ?? 0;

        $ratio = $value / $threshold->target * 100;

        if ($ratio < 50) {
            $status = 'GOOD';
        } else if ($ratio < 80) {
            $status = 'WARNING';
        } else {
            $status = 'DANGER';
        }

        return [
            'history' => $this->stress_level ?? [],
            'value' => $value,
            'target' => $threshold->target,
            'ratio' => $ratio,
            'status' => $status,
            'point' => (100 - $ratio) * $threshold->ratio / 100,
        ];
    }

    public function masterPoint()
    {
        $threshold = $this->thresholds('MASTER_POINT');

        if (!$threshold) {
            return;
        }

        $ratio = $this->master_point / $threshold->target * 100;

        if ($ratio < 50) {
            $status = 'DANGER';
        } else if ($ratio < 80) {
            $status = 'WARNING';
        } else {
            $status = 'sucess';
        }

        return [
            'value' => $this->cholesterol,
            'target' => $threshold->target,
            'ratio' => $ratio,
            'status' => $status,
            'point' => min($ratio, 100) * $threshold->ratio / 100,
        ];
    }

    public function getPointDataAttribute($key = '')
    {
        if ($this->point < 50) {
            $status = 'DANGER';
        } else if ($this->point < 70) {
            $status = 'WARNING';
        } else {
            $status = 'GOOD';
        }

        $res = collect([
            'step' => $this->stepPoint(),
            'workout_duration' => $this->workoutDurationPoint(),
            'bmi' => $this->bmiPoint(),
            'waist_size' => $this->waistSizePoint(),
            'hydration' => $this->hydrationPoint(),
            'sleep_duration' => $this->sleepPoint(),
            'systolic' => $this->systolicPoint(),
            'diastolic' => $this->diastolicPoint(),
            'blood_glucose' => $this->blodGlucosePoint(),
            'cholesterol' => $this->cholesterolPoint(),
            'stress_level' => $this->stressLevelPoint(),
            'point' => [
                'value' => $this->point,
                'target' => 100,
                'status' => $status,
                'fitness' => $this->fitness,
            ],
        ]);

        if ($key) {
            return $res[$key];
        }

        return $res->filter(fn($item) => $item);
    }

    public static function bmi_calculate($weight, $height)
    {
        if ($weight && $height) {
            $bmi = $weight / (($height / 100) * ($height / 100));

            // You can then return or use the $bmi as needed
            return $bmi;
        } else {
            return 0;
        }
    }

    public static function fitScore($final_score)
    {
        if ($final_score >= 50) {
            return 'fit';
        } elseif ($final_score <= 40) {
            return 'temporary unfit';
        } else {
            return 'unfit';
        }
    }
}

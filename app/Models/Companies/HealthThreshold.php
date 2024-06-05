<?php

namespace App\Models\Companies;

use App\Models\Model;
use App\Models\Traits\BelongsToCompany;
use App\Models\Traits\HasCreator;

class HealthThreshold extends Model
{
    use BelongsToCompany, HasCreator;

    public static $THRESHOLDS = [
        'STEP'             => [
            'name'   => 'Step',
            'code'   => 'STEP',
            'target' => 10000,
            'ratio'  => '10.0',
        ],
        'WORKOUT_DURATION' => [
            'name'   => 'Workout Duration',
            'code'   => 'WORKOUT_DURATION',
            'target' => 10,
            'ratio'  => '10.0',
        ],
        'SLEEP_DURATION'   => [
            'name'   => 'Sleep Duration',
            'code'   => 'SLEEP_DURATION',
            'target' => 6,
            'ratio'  => '30.0',
        ],
        'HYDRATION'        => [
            'name'   => 'Hydration',
            'code'   => 'HYDRATION',
            'target' => 8,
            'ratio'  => '5.0',
        ],
        'BMI'              => [
            'name'   => 'Bmi',
            'code'   => 'BMI',
            'target' => 0,
            'ratio'  => '7.5',
        ],
        'MEN_WAIST_SIZE'   => [
            'name'   => 'Men Waist Size',
            'code'   => 'MEN_WAIST_SIZE',
            'target' => 90,
            'ratio'  => '7.5',
        ],
        'WOMEN_WAIST_SIZE' => [
            'name'   => 'Women Waist Size',
            'code'   => 'WOMEN_WAIST_SIZE',
            'target' => 80,
            'ratio'  => '7.5',
        ],
        'BLOOD_SUGAR'      => [
            'name'   => 'Blood Sugar',
            'code'   => 'BLOOD_SUGAR',
            'target' => 200,
            'ratio'  => '10.5',
        ],
        'CHOLESTEROL'      => [
            'name'   => 'Cholesterol',
            'code'   => 'CHOLESTEROL',
            'target' => 300,
            'ratio'  => '10.5',
        ],
        'STRESS_LEVEL'     => [
            'name'   => 'Stress Level',
            'code'   => 'STRESS_LEVEL',
            'target' => 10,
            'ratio'  => '25.0',
        ],
        'MASTER_POINT'     => [
            'name'   => 'Master Point',
            'code'   => 'MASTER_POINT',
            'target' => 100,
            'ratio'  => '25.0',
        ],
        'SYSTOLIC'         => [
            'name'   => 'Systolic',
            'code'   => 'SYSTOLIC',
            'target' => 100,
            'ratio'  => '4.5',
        ],
        'DIASTOLIC'        => [
            'name'   => 'Diastolic',
            'code'   => 'DIASTOLIC',
            'target' => 100,
            'ratio'  => '4.5',
        ],
        'ACTIVITY_POINT'   => [
            'name'   => 'Activity Point',
            'code'   => 'ACTIVITY_POINT',
            'target' => 10,
            'ratio'  => '50.0',
        ],
        'HEART_RATE'       => [
            'name'   => 'Heart Rate',
            'code'   => 'HEART_RATE',
            'target' => 100,
            'ratio'  => '10.0',
        ],
    ];

    public static $PSS = [
        [
            "id"          => 1,
            "description" => "Dalam 1 bulan terakhir, seberapa sering Anda marah karena sesuatu tidak terduga?",
        ],
        [
            "id"          => 2,
            "description" => "Dalam 1 bulan terakhir, seberapa sering Anda merasa tidak mampu mengontrol hal-hal yang penting dalam kehidupan Anda?",
        ],
        [
            "id"          => 3,
            "description" => "Dalam 1 bulan terakhir, seberapa sering Anda merasa gelisah dan tertekan?",
        ],
        [
            "id"          => 4,
            "description" => "Dalam 1 bulan terkahir, seberapa sering Anda merasa yakin terhadap kemampuan diri untuk mengatasi masalah pribadi?",
        ],
        [
            "id"          => 5,
            "description" => "Dalam 1 bulan terakhir, seberapa sering Anda merasa segala sesuatu yang terjadi sesuai dengan harapan Anda?",
        ],
        [
            "id"          => 6,
            "description" => "Dalam 1 bulan terakhir, seberapa sering Anda merasa tidak mampu menyelesaikan hal-hal yang harus dikerjakan?",
        ],
        [
            "id"          => 7,
            "description" => "Dalam 1 bulan terakhir, seberapa sering Anda mampu mengontrol rasa mudah tersinggung dalam kehidupan Anda?",
        ],
        [
            "id"          => 8,
            "description" => "Dalam 1 bulan terakhir, seberapa sering Anda merasa lebih mampu mengatasi masalah jika dibandingkan dengan orang lain?",
        ],
        [
            "id"          => 9,
            "description" => "Dalam 1 bulan terakhir, seberapa sering Anda marah karena adanya masalah yang tidak dapat Anda kendalikan?",
        ],
        [
            "id"          => 10,
            "description" => "Dalam 1 bulan terakhir, seberapa sering Anda merasakan kesulitan yang menumpuk sehingga Anda tidak mampu untuk mengatasinya?",
        ],
    ];

    public static $PSS_Answer = [
        [
            "id"    => 0,
            "point" => 4,
            "label" => 'Tidak Pernah',
        ],
        [
            "id"    => 1,
            "point" => 3,
            "label" => 'Hampir tidak pernah (1-2 kali)',
        ],
        [
            "id"    => 2,
            "point" => 2,
            "label" => 'Kadang-kadang (3-4 kali)',
        ],
        [
            "id"    => 3,
            "point" => 1,
            "label" => 'Hampir sering (5-6 kali)',
        ],
        [
            "id"    => 4,
            "point" => 0,
            "label" => 'Sangat sering (lebih dari 6 kali)',
        ],
    ];

    public $fillable = [
        'company_id',
        'name',
        'code',
        'target',
        'ratio',
    ];
}

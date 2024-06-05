<?php

namespace App\Observers\Healths;

use App\Observers\Observer;

class HealthObserver extends Observer
{
    public function saving($model)
    {
        // $model->point = $model->member->created_at->addWeeks(1)->isPast()
        // ? $model->pointData->sum('point')
        // : $model->point;

        // if ($model->point < 50) {
        //     $model->fitness = 'UNFIT';
        // } else if ($model->point < 70) {
        //     $model->fitness = 'TEMPORARY UNFIT';
        // } else {
        //     $model->fitness = 'FIT';
        // }

        parent::saving($model);
    }
}

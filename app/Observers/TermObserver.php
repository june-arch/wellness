<?php

namespace App\Observers;

use App\Observers\Observer;

class TermObserver extends Observer
{
    public function saving($model)
    {
        parent::saving($model);
    }

    public function saved($model)
    {
        parent::saved($model);
    }

    public function deleted($model)
    {
        parent::deleted($model);
    }
}

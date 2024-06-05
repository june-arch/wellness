<?php

namespace App\Observers\Users;

use App\Observers\Observer;
use Illuminate\Support\Str;

class UserObserver extends Observer
{
    public function creating($model)
    {
        $model->api_token = Str::random(60);

        parent::creating($model);
    }

    public function saving($model)
    {
        if ($model->isDirty('password')) {
            $model->password = bcrypt($model->password);
        }

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

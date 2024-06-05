<?php

namespace App\Observers;

class CommentObserver extends Observer
{

    public function creating($model)
    {
        $model->type = get_class($model);
        parent::creating($model);
    }

    public function updating($model)
    {
        parent::updating($model);
        unset($model->type);
    }
}

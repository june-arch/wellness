<?php

namespace App\Observers\Users;

class LogObserver
{
    public function creating($model)
    {
        $request = request();

        $model->user_id = auth()->user()->id;
        $model->data = $model->data ?? json_encode($request->post());
        $model->method = $model->method ?? $request->method() ?? 'put';
        $model->path = $request->path();
        $model->ip_address = $request->ip();
    }
}

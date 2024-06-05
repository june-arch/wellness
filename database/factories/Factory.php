<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;

abstract class Factory extends BaseFactory
{
    protected function firstOrCreate($model)
    {
        return $model->inRandomOrder()->first()->id ?? $model->factory()->create()->id;
    }
}

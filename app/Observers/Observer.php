<?php

namespace App\Observers;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Observer
{
    protected $journals;

    public function __construct()
    {
        $this->journals = collect();
    }

    public function creating($model)
    {
        if (method_exists($model, 'created_by') && Auth::id()) {
            $model->created_by_id = Auth::id();
        }
    }

    public function created($model)
    {
        $this->createLog($model, 'create');
    }

    public function updating($model)
    {
    }

    public function updated($model)
    {
        $this->createLog($model, 'update');
    }

    public function saving($model)
    {
        if (method_exists($model, 'updated_by') && Auth::id()) {
            $model->updated_by_id = Auth::id();
        }
    }

    public function saved($model)
    {
    }

    public function deleting($model)
    {
    }

    public function deleted($model)
    {
        $this->createLog($model, 'delete');
    }

    protected static function setting($key)
    {
        return Setting::where('key', $key)->first()->value;
    }

    protected function createLog($model, $method)
    {
        if (method_exists($model, 'logs') && auth()->check()) {
            $model->logs()->create([
                'logable_id'   => $model->id,
                'logable_type' => get_class($model),
                'method'       => $method,
                'data'         => $model->getDirty(),
            ]);
        }
    }

    protected function errorValidation($field, $messege)
    {
        throw ValidationException::withMessages([$field => [$messege]]);
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as HttpFormRequest;
use Illuminate\Validation\Rule;

class FormRequest extends HttpFormRequest
{
    protected $paramKey;
    protected $table;

    public function rules()
    {
        return [];
    }

    protected function isCreate()
    {
        return in_array('POST', $this->route()->methods);
    }

    protected function isUpdate()
    {
        return in_array('PUT', $this->route()->methods);
    }

    protected function param($key = null)
    {
        $routeParam = collect($this->route()->parameter($this->paramKey))->first();
        return !is_null($key) && is_object($routeParam) ? $routeParam->$key : $routeParam;
    }

    protected function unique(string $table, string $column = null)
    {
        if ($this->isCreate()) {
            return "unique:{$table}";
        }

        if ($this->isUpdate()) {
            return "unique:{$table},{$column},{$this->param('id')}";
        }
    }

    protected function existsWithParams(string $table, string $column, array $data)
    {
        return Rule::exists($table, $column)->where(function ($query) use ($data) {
            foreach ($data as $key => $value) {
                $query->where($key, $value);
            }
        });
    }

    protected function uniqueWithParams(string $table, string $column, array $data)
    {
        $rules = Rule::unique($table, $column)->where(function ($query) use ($data) {
            foreach ($data as $key => $value) {
                $query->where($key, $value);
            }
        });

        if ($this->isUpdate()) {
            return $rules->ignore($this->param('id'));
        }

        return $rules;
    }

    protected function setFilesRequest($model)
    {
        if ($this->isUpdate()) {
            $model = (new $model)->find($this->param('id'));

            if ($model) {
                $max = 5 - $model->getMedia('files')->count();
                return ['array', "max:{$max}"];
            }
        }
    }
}

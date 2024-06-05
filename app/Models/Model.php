<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getTableWithPrefix()
    {
        return $this->getConnection()->getTablePrefix() . $this->getTable();
    }

    public function clientUrl(string $configKey, $field = 'slug'): string
    {
        $config = config('client');
        $path   = $field === false ?: '/' . str_replace("{{$field}}", $this->$field, $config[$configKey]);

        return $config['base_url'] . $path;
    }

    public static function columnExists(string $column): bool
    {
        return collect(self::$fillable)->include($column);
    }
}

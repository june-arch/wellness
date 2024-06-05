<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $query;
    protected string $table;

    protected function cacheName($key, $id = null)
    {
        $parseQuery = collect($_GET)->sortKeys()->map(function ($value, $key) {
            return "{$key}_{$value}";
        })->join('_');
        return Str::of($key . '_' . $id . $parseQuery)->replace(['::', "\\"], '_')->trim('_')->upper()->__toString();
    }

    protected function res($message, int $statusCode = 200)
    {
        return response()->json($message, $statusCode);
    }

    protected function success($data, $message = 'Success get data', int $statusCode = 200)
    {
        $content = [
            'status'      => 'success',
            'status_code' => $statusCode,
            'message'     => $message,
        ];

        if ($data) {
            $content['data'] = $data;
        }

        return response()->json($content, $statusCode);
    }

    protected function created($data, $message = 'Success created data')
    {
        return $this->success($data, $message, 201);
    }

    protected function error($message = 'Success get data', int $statusCode = 400)
    {
        return $this->res([
            'status'      => 'error',
            'status_code' => $statusCode,
            'message'     => $message,
        ], $statusCode);
    }

    protected function collection($collections, $message = 'Success get data')
    {
        return response()->json([
            'status'      => 'success',
            'status_code' => 200,
            'message'     => $message,
            'data'        => $collections,
            'meta'        => [
                'total'       => $collections->total(),
                'perPage'     => $collections->perPage(),
                'currentPage' => $collections->currentPage(),
                'lastPage'    => $collections->lastPage(),
                'prevPage'    => $collections->currentPage() > 1 ? $collections->currentPage() - 1 : null,
                'nextPage'    => $collections->lastPage() !== $collections->currentPage() ? $collections->currentPage() + 1 : null,
            ],
        ], 200);
    }

    protected static function errorValidation($field, $messege)
    {
        throw ValidationException::withMessages([$field => [$messege]]);
    }

    public function types()
    {
        $data   = '';
        $path   = app_path('Models');
        $models = $this->getModels($path)->flatten()->sort(function ($item) {
            return Str::of($item)->explode('\\')->last();
        });

        foreach ($models as $model) {

            if (class_exists($model)) {
                $res  = (new $model)->first();
                $name = Str::of(class_basename($model))->studly()->__toString();

                if ($res) {
                    $data .= "export type {$name} = {\n";
                    foreach ($res->toArray() as $key => $value) {
                        if (is_int($value)) {
                            $value = 'number';
                        } else if (Str::of($key)->endsWith('_type')) {
                            continue;
                        } else if ($key === 'category_id') {
                            $key   = 'category';
                            $value = "{$name}Category";
                        } else if (Str::of($key)->startsWith('is_')) {
                            $value = 'boolean';
                        } else if (Str::of($key)->endsWith('by_id')) {
                            $key   = Str::of($key)->replace('_id', '')->__toString();
                            $value = 'User';
                        } else if (Str::of($key)->endsWith('_id')) {
                            $key   = Str::of($key)->replace('_id', '')->__toString();
                            $value = $key === 'parent' ? $name : Str::of($key)->replace('_id', '')->studly()->__toString();
                        } elseif (is_array($value)) {
                            $value = Str::of($key)->replace('_id', '')->__toString() . '[]';
                        } else {
                            $value = 'string';
                        }

                        if (!Str::of($key)->endsWith('able')) {
                            $data .= "    {$key}: {$value};\n";
                        }
                    }
                    $data .= "}\n\n";
                } elseif ((new $model)->fillable) {
                    $data .= "export type {$name} = {\n";
                    foreach ((new $model)->fillable as $key => $value) {
                        if (!Str::of($key)->endsWith('able')) {
                            $data .= "    {$value}: string;\n";
                        }
                    }
                    $data .= "}\n\n";
                }
            }
        }

        return $data;
    }

    protected function getModels($path): Collection
    {
        $data   = collect();
        $models = scandir($path);

        foreach ($models as $model) {
            if (in_array($model, ['.', '..', 'Model.php', 'Traits', 'Meta', 'Setting.php'])) {
                continue;
            }

            $fullPath = "{$path}/{$model}";

            if (is_dir($fullPath)) {
                $data->push($this->getModels($fullPath));
            } else if (Str::of($fullPath)->endsWith('.php')) {
                $className = Str::of($fullPath)->replace(app_path(), 'App')->replace('.php', '')->replace('/', '\\')->__toString();

                $data->push($className);
            }
        }

        return $data;
    }

    protected function saveThumbnail($model)
    {
        if (method_exists($model, 'saveThumbnail')) {
            $model->saveThumbnail(request());
        }
    }

    protected function saveMedia($model)
    {
        if (method_exists($model, 'saveMedia')) {
            $model->saveMedia(request());
        }
    }

    protected function saveGalery($model)
    {
        if (method_exists($model, 'saveGalery')) {
            $model->saveGalery(request());
        }
    }
}

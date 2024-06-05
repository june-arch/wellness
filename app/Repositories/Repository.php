<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Repository
{
    protected $query;
    protected $request;

    public static function parsedRequestFilter(Request $request, Builder $query, $allowedData)
    {
        $allowedFilters       = $allowedData['filters'];
        $allowedGlobalFilters = $allowedData['globalFilters'];
        $allowedOrders        = $allowedData['orders'];

        $filtersData = $request->query();

        if (gettype($request->filters) === 'string') {
            $filtersData['filters'] = json_decode($request->filters, true);
        }

        if (gettype($request->sorting) === 'string') {
            $filtersData['sorting'] = json_decode($request->sorting, true);
        }

        $validation = validator($filtersData, [
            'filters'         => ['array'],
            'filters.*.id'    => ['required', "in:" . join(',', $allowedFilters)],
            'filters.*.value' => ['required', 'string'],
            'sorting'         => ['array'],
            'sorting.*.id'    => ['required', "in:" . join(',', $allowedOrders)],
            'sorting.*.desc'  => ['required', 'boolean'],
            'globalFilter'    => ['string', 'nullable'],
        ]);

        $validation->validate();

        $filtersData = $validation->validated();

        if (count($allowedFilters) > 0 && $request->filters) {
            foreach ($filtersData['filters'] as $filter) {
                $key   = $filter['id'];
                $value = $filter['value'];

                if (!$key || !$value || !in_array($key, $allowedFilters)) {
                    continue;
                }

                self::deepFilter($query, $value, explode('.', $key));
            }
        }

        if (count($allowedOrders) > 0 && $request->sorting) {
            foreach ($filtersData['sorting'] as $sort) {
                $key   = $sort['id'];
                $order = $sort['desc'] ? 'desc' : 'asc';

                if (!in_array($key, $allowedOrders)) {
                    continue;
                }

                $query->orderBy($key, $order);
            }
        }

        if (is_array($allowedGlobalFilters) && count($allowedGlobalFilters) > 0 && $request->globalFilter) {
            $query->where(function ($q) use ($filtersData, $allowedGlobalFilters) {
                foreach ($allowedGlobalFilters as $key) {
                    $q->orWhere($key, 'LIKE', "%{$filtersData['globalFilter']}%");
                }
            });
        }
    }

    protected static function deepFilter(Builder $query, $value, $relations = [])
    {
        $key = $relations[0];

        if ($key && $key != '') {
            if (count($relations) > 1) {
                $query->whereHas($key, function ($q) use ($relations, $value) {
                    array_shift($relations);
                    self::deepFilter($q, $value, $relations);
                });
            } else {
                if ($key === 'id') {
                    $query->where($key, $value);
                } else {
                    $query->where($key, 'LIKE', "%{$value}%");

                }
            }
        }
    }

    protected function filter($fields = [], $relations = [])
    {
        $selects = collect();

        foreach ($relations as $relation) {
            if (isset($relation['fields'])) {
                $relationName  = $relation['name'];
                $relationTable = $relation['table'] ?? (new Model)->getConnection()->getTablePrefix() . Str::plural($relation['name']);
                $foreignId     = $relation['foreign_id'] ?? 'id';

                $relationId = $relation['id'] ?? $relation['name'] . '_id';

                foreach ($relation['fields'] as $field) {
                    $fields[] = "{$relationName}_{$field}";
                    $selects->push("(select $relationTable.$field from $relationTable where $relationTable.$foreignId = $relationId) AS {$relationName}_{$field}");
                }
            }
        }

        if ($selects->isNotEmpty()) {
            $this->query->selectRaw($selects->implode(','));
        }

        $this->filterMatch($fields);

        if ($this->request->has('q') && $this->query->getModel()->keywordField) {
            $this->query->where($this->query->getModel()->keywordField, 'like', "%{$this->request->q}%");
        }

        if ($this->request->has('orderby')) {
            $orderBy = str_replace('.', '_', $this->request->orderby);

            if (in_array($orderBy, $fields)) {
                $this->query->orderBy($orderBy, $this->request->sort ?? 'desc');
            }
        }

        $this->query->toSql();
    }

    protected function filterMatch($params)
    {
        foreach ($params as $param) {
            $min      = "{$param}>";
            $max      = "{$param}<";
            $whereNot = "{$param}!";
            $notLike  = "{$param}~!";
            $like     = "{$param}~";
            $notIn    = "{$param}_not_in";
            $in       = "{$param}_in";

            if ($this->request->has($param)) {
                $this->query->having($param, $this->request->$param);
            }

            if ($this->request->has($whereNot)) {
                $this->query->having($param, '!=', $this->request->$whereNot);
            }

            if ($this->request->has($min)) {
                $this->query->having($param, '>=', $this->request->$min);
            }

            if ($this->request->has($max)) {
                $this->query->having($param, '<=', $this->request->$max);
            }

            if ($this->request->has($like)) {
                $this->query->having($param, 'like', "%{$this->request->$like}%");
            }

            if ($this->request->has($notLike)) {
                $this->query->having($param, 'not like', "%{$this->request->$notLike}%");
            }

            if ($this->request->has($notIn)) {
                $this->query->havingRaw("{$param} notin (" . explode(',', $this->request->$notIn) . ")");
            }

            if ($this->request->has($in)) {
                $this->query->havingRaw("{$param} in (" . explode(',', $this->request->$in) . ")");
            }
        }
    }
}

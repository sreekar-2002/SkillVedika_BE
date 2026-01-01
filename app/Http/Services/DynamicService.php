<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

class DynamicService
{
    public static function store(Model $model, array $data)
    {
        $columns = Schema::getColumnListing($model->getTable());

        $filtered = collect($data)
            ->filter(fn($v, $k) => in_array($k, $columns))
            ->toArray();

        return $model::create($filtered);
    }

    public static function update(Model $model, array $data)
    {
        $columns = Schema::getColumnListing($model->getTable());

        $filtered = collect($data)
            ->filter(fn($v, $k) => in_array($k, $columns))
            ->toArray();

        $model->update($filtered);

        return $model;
    }
}


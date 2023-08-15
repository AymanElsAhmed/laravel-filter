<?php

namespace AymanEls\LaravelFilterPackage\Traits;

trait Filter
{
    public static function filter()
    {
        $model = new static();
        $request = request();

        $query = $model->newQuery();

        if ($request->has('search') && isset(static::$filterSearchCols)) {
            $model = $model->where(function ($q) use ($request) {
                foreach (static::$filterSearchCols as $column) {
                    $q->orWhere($column, 'like', "%{$request->search}%");
                }
            });
        }

        if (isset(static::$filterCols)) {
            foreach (static::$filterCols as $column) {
                if ($request->has($column))
                    $model = $model->where($column, $request->$column);
            }
        }


        if (isset(static::$filterColsChilds)) {
            foreach (static::$filterColsChilds as $column => $child) {
                if ($request->has($child . "_" . $column))
                    $model = $model->whereHas($child, function ($q) use ($column, $request, $child) {
                        $q->where($column, $request[$child . "_" . $column]);
                    });
            }
        }

        if (isset(static::$filterColsBetween)) {
            foreach (static::$filterColsBetween as $column) {
                if ($request->has($column))
                    $model = $model->whereBetween($column, [$request->$column[0], $request->$column[1]]);
            }
        }

        if (isset(static::$filterColsBetweenChilds)) {
            foreach (static::$filterColsBetweenChilds as $column => $child) {
                if ($request->has($child . "_" . $column))
                    $model = $model->whereHas($child, function ($q) use ($column, $request, $child) {
                        $q->whereBetween($column, [$request[$child . "_" . $column][0], $request[$child . "_" . $column][1]]);
                    });
            }
        }



        if (isset(static::$filterDates)) {
            $i = 0;
            foreach (static::$filterDates as $input => $column) {
                if ($request->has($input))
                    if ($i == 0)
                        $model = $model->where($column, '>=', $request->$input);
                    else
                        $model = $model->where($column, '<=', $request->$input);
                $i++;
            }
        }

        if (isset(static::$filterDatesChilds)) {
            $i = 0;
            foreach (static::$filterDatesChilds as $input => $column) {
                if ($request->has($input))
                    if ($i == 0)
                        $model = $model->whereHas($column, function ($q) use ($input, $request, $column) {
                            $q->where($column, '>=', $request->$input);
                        });
                    else
                        $model = $model->whereHas($column, function ($q) use ($input, $request, $column) {
                            $q->where($column, '<=', $request->$input);
                        });
                $i++;
            }
        }

        if (isset(static::$filterDatesBetween)) {
            foreach (static::$filterDatesBetween as $input => $column) {
                if ($request->has($input))
                    $model = $model->whereBetween($column, [$request->$input[0], $request->$input[1]]);
            }
        }

        if (isset(static::$filterDatesBetweenChilds)) {
            foreach (static::$filterDatesBetweenChilds as $input => $column) {
                if ($request->has($input))
                    $model = $model->whereHas($column, function ($q) use ($input, $request, $column) {
                        $q->whereBetween($column, [$request->$input[0], $request->$input[1]]);
                    });
            }
        }


        return $model;
    }
}
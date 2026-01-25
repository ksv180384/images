<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class QueryFilter
{
    protected $request;

    protected $builder;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->fields() as $field => $value) {
            $method = Str::camel($field);
            if(method_exists($this, $method)){
                call_user_func_array([$this, $method], array_filter([$value]));
            }
        }

        return $this->builder;
    }

    /**
     * Получаем массив методов из параметров запроса
     * @return array
     */
    public function fields(): array
    {
        return array_filter(
            array_map(function ($item) {
                if(is_string($item)){
                    $item = trim($item);
                }
                return $item;
            }, $this->request->all())
        );
    }
}

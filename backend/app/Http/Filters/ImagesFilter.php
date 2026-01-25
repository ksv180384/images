<?php

namespace App\Http\Filters;

class ImagesFilter extends QueryFilter
{
    /**
     * Выбираем, которе содержат теги указанные в $params
     * @param $params
     * @return mixed
     */
    public function exist($params)
    {
        return $this->builder->whereIn('id', function($query) use ($params) {
            return $query->select('image_id')->from('image_tags_to_images')->whereIn('image_tag_id', $params);
        });
    }

    /**
     * Исключаем, которые содержат теги указанные в $params
     * @param $params
     * @return mixed
     */
    public function notExist($params)
    {
        return $this->builder->whereNotIn('id', function($query) use ($params) {
            return $query->select('image_id')->from('image_tags_to_images')->whereIn('image_tag_id', $params);
        });
    }

    /**
     * Промежуток даты создания фотографии
     * @param $params
     * @return mixed
     */
    public function dateRange($params)
    {
        return $this->builder->whereBetween('image_created_at', [$params]);
    }

    public function noDate($params)
    {
        return $this->builder->whereNull('image_created_at');
    }
}

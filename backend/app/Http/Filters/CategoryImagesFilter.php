<?php

namespace App\Http\Filters;

class CategoryImagesFilter extends QueryFilter
{
    /**
     * Фильтрация по тегам.
     *
     * Ожидается строка формата "id-1,id2-0,...",
     * где "1" — положительный фильтр (должен содержать тег),
     * "0" — отрицательный фильтр (не должен содержать тег).
     *
     * @param string $value
     * @return mixed
     */
    public function tags(string $value)
    {
        $positive = [];
        $negative = [];

        foreach (explode(',', $value) as $item) {
            $item = trim($item);
            if ($item === '') {
                continue;
            }

            [$id, $flag] = array_pad(explode('-', $item, 2), 2, null);

            if (!$id || ($flag !== '0' && $flag !== '1')) {
                continue;
            }

            if ($flag === '1') {
                $positive[] = $id;
            } elseif ($flag === '0') {
                $negative[] = $id;
            }
        }

        if (!empty($positive)) {
            $this->builder->whereIn('id', function ($query) use ($positive) {
                return $query
                    ->select('image_id')
                    ->from('image_tags_to_images')
                    ->whereIn('image_tag_id', $positive);
            });
        }

        if (!empty($negative)) {
            $this->builder->whereNotIn('id', function ($query) use ($negative) {
                return $query
                    ->select('image_id')
                    ->from('image_tags_to_images')
                    ->whereIn('image_tag_id', $negative);
            });
        }

        return $this->builder;
    }

    /**
     * Нижняя граница диапазона дат (image_created_at >= range_from)
     *
     * @param string $from
     * @return mixed
     */
    public function rangeFrom(string $from)
    {
        $trimmed = trim($from);
        if ($trimmed === '') {
            return $this->builder;
        }

        try {
            // Игнорируем день/время: устанавливаем начало месяца
            $date = \Carbon\Carbon::parse($trimmed)->startOfMonth();
        } catch (\Exception $e) {
            // Некорректная дата — пропускаем фильтр (можно добавить логирование)
            return $this->builder;
        }
    }

    /**
     * Верхняя граница диапазона дат (image_created_at <= range_to)
     *
     * @param string $to
     * @return mixed
     */
    public function rangeTo(string $to)
    {
        $trimmed = trim($to);
        if ($trimmed === '') {
            return $this->builder;
        }

        try {
            // Игнорируем день/время: устанавливаем конец месяца (включая время 23:59:59.999999)
            $date = \Carbon\Carbon::parse($trimmed)->endOfMonth();
        } catch (\Exception $e) {
            // Некорректная дата — пропускаем фильтр (можно добавить логирование)
            return $this->builder;
        }

        return $this->builder->where('image_created_at', '<=', $date);
    }

    /**
     * Фильтр "без даты" — выбираем записи без даты создания.
     *
     * @param $params
     * @return mixed
     */
    public function noDate($params)
    {
        return $this->builder->whereNull('image_created_at');
    }
}

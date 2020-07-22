<?php
/**
 *
 *
 */
namespace QuickPay\Concerns;

trait Filterable
{
    public function filterJson($filter = [], $json_arr = [])
    {
        $filtered = array_filter(
            $json_arr,
            function ($key) use ($filter) {
                if (in_array($key, $filter)) {
                    return $key;
                }
            },
            ARRAY_FILTER_USE_KEY
        );
        return $filtered;
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    public function toQueryString()
    {
        return http_build_query($this->attributesToArray());
    }

    public function toFormArray($filter = [])
    {
        $attributes = $this->toArray();
        if (count($filter)) {
            foreach ($attributes as $key => $val) {
                if (in_array($key, $filter)) {
                    unset($attributes[$key]);
                }
            }
        }
        return $attributes;
    }
}

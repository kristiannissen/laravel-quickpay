<?php
/**
 * Abstract base class
 */
namespace QuickPay;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;

abstract class QuickPayModel implements Jsonable, Arrayable
{
    protected $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Convert the model instance to JSON.
     *
     * @param int $options
     * @return string
     * TODO: Create specific exception
     * @throws \Exception
     */
    public function toJson($options = 0)
    {
        $json = json_encode($this->attributes, $options);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \Exception(json_last_error_msg());
        }
        return $json;
    }

    /**
     * Convert the object into something JSON serializable
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return $this
     * TODO: Make specific exception
     * @throws \Exception
     */
    public function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     *
     */
    public function setAttribute($key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     *
     */
    public function getAttribute($key)
    {
        return $this->attributes[$key];
    }

    /**
     *
     */
    public function __set($key, $value): void
    {
        $this->setAttribute($key, $value);
    }

    /**
     *
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }
}

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

    protected $fillable = [];

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
        $json = json_encode($this->getAttributes(), $options);
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
     * @return
     * @throws \Exception
     */
    public function fill(array $attributes): void
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            } else {
                throw new \Exception(
                    sprintf(
                        'Add [%s] to fillable allow mass assignment on [%s].',
                        $key,
                        get_class($this)
                    )
                );
            }
        }
    }

    public function isFillable(string $key): bool
    {
        return in_array($key, $this->getFillable());
    }

    /**
     */
    public function getFillable()
    {
        return $this->fillable;
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
        if (array_key_exists($key, $this->getAttributes())) {
            return $this->attributes[$key];
        }
        throw new \Exception(
            sprintf('[%s] is not a property of [%s]', $key, get_class($this))
        );
    }

    /**
     *
     */
    public function getAttributes()
    {
        return $this->attributes;
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

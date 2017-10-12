<?php

namespace Purist\Struct;

class IndexedArrayValue implements Value
{
    private $value;

    public function __construct(?Value $value = null)
    {
        $this->value = $value ?? new AnyValue();
    }

    public function validate($values): bool
    {
        if (
            !is_array($values)
            || (
                $values !== []
                && array_keys($values) !== range(0, count($values) - 1)
            )
        ) {
            return false;
        }

        foreach ($values as $value) {
            if ($this->value->validate($value) ?? null) {
                continue;
            }

            return false;
        }

        return true;
    }

    public function get($values): array
    {
        if (!is_array($values)) {
            throw new \InvalidArgumentException(
                sprintf('An array was expected but %s was passed', gettype($values))
            );
        }

        if ($values !== [] && array_keys($values) !== range(0, count($values) - 1)) {
            throw new \InvalidArgumentException('The passed array was not indexed.');
        }

        return array_map(
            function($value) {
                return $this->value->get($value);
            },
            $values
        );
    }
}

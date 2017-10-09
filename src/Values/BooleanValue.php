<?php
declare(strict_types=1);

namespace Purist\Struct\Values;

final class BooleanValue implements Value
{
    public function validate($value): bool
    {
        return $value !== null && filter_var(
            $value,
            FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE
        ) !== null;
    }

    public function get($value): bool
    {
        if (!$this->validate($value)) {
            throw new \InvalidArgumentException(
                sprintf('%s (%s) is not a valid boolean', $value, gettype($value))
            );
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}

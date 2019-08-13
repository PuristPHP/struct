<?php
declare(strict_types=1);

namespace Purist\Struct;

final class FloatValue implements Value
{
    public function validate($value): bool
    {
        return is_bool($value) === false
            && filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
    }

    public function get($value): float
    {
        if (!$this->validate($value)) {
            throw new \InvalidArgumentException(
                sprintf('%s (%s) is not a valid float', $value, gettype($value))
            );
        }

        return filter_var($value, FILTER_VALIDATE_FLOAT);
    }
}

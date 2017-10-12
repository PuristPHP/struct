<?php
declare(strict_types=1);

namespace Purist\Struct;

final class IntegerValue implements Value
{
    public function validate($value): bool
    {
        return is_bool($value) === false && filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    public function get($value): int
    {
        if (!$this->validate($value)) {
            throw new \InvalidArgumentException(
                sprintf('%s (%s) is not a valid integer', $value, gettype($value))
            );
        }

        return (int) $value;
    }
}

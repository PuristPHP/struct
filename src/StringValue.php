<?php
declare(strict_types=1);

namespace Purist\Struct;

final class StringValue implements Value
{
    public function validate($value): bool
    {
        return is_string($value)
            || (is_object($value) && method_exists($value, '__toString'));
    }

    public function get($value): string
    {
        if (!$this->validate($value)) {
            throw new \InvalidArgumentException(
                sprintf('%s (%s) is not a valid string', $value, gettype($value))
            );
        }

        return $value;
    }
}

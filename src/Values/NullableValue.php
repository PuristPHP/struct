<?php
declare(strict_types=1);

namespace Purist\Struct\Values;

final class NullableValue implements Value
{
    private $value;

    public function __construct(Value $value)
    {
        $this->value = $value;
    }

    public function validate($value): bool
    {
        return $value === null || $value === '' || $this->value->validate($value);
    }

    public function get($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        return $this->value->get($value);
    }
}

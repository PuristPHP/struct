<?php
declare(strict_types=1);

namespace Purist\Struct\Constraint;

final class Minimum implements Constraint
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function validate($value): bool
    {
        return $this->value <= $value;
    }

    public function __toString(): string
    {
        return sprintf('minimum: %f', $this->value);
    }
}

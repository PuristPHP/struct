<?php
declare(strict_types=1);

namespace Purist\Struct\Constraint;

interface Constraint
{
    public function validate($value): bool;
    public function __toString(): string;
}

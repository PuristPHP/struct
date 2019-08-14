<?php
declare(strict_types=1);

namespace Purist\Struct;

use Purist\Struct\Constraint\Constraint;

final class IntegerValue implements Value
{
    private $constraints;

    public function __construct(Constraint ...$constraints)
    {
        $this->constraints = $constraints;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if (is_bool($value) || filter_var($value, FILTER_VALIDATE_INT) === false) {
            return false;
        }

        foreach ($this->constraints as $constraint) {
            if (!$constraint->validate($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function get($value): int
    {
        if (!$this->validate($value)) {
            throw ValidationFailed::value('integer', $value, null, ...$this->constraints);
        }

        return (int) $value;
    }
}

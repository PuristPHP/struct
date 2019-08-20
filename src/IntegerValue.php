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
    public function validate($value): Validation
    {
        if (is_bool($value) || filter_var($value, FILTER_VALIDATE_INT) === false) {
            return Validation::failedValue('IntegerValue', $value);
        }

        foreach ($this->constraints as $constraint) {
            if (!$constraint->validate($value)) {
                return Validation::failedValue('IntegerValue', $value, null, ...$this->constraints);
            }
        }

        return Validation::successful();
    }

    /**
     * @inheritDoc
     */
    public function get($value): int
    {
        $validation = $this->validate($value);

        if ($validation->hasErrors()) {
            throw ValidationFailed::fromValidation($validation);
        }

        return (int) $value;
    }
}

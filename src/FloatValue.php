<?php
declare(strict_types=1);

namespace Purist\Struct;

final class FloatValue implements Value
{
    /**
     * @inheritDoc
     */
    public function validate($value): Validation
    {
        if (
            is_bool($value) === false
            && filter_var($value, FILTER_VALIDATE_FLOAT) !== false
        ) {
            return Validation::successful();
        }

        return Validation::failedValue('float', $value);
    }

    /**
     * @inheritDoc
     */
    public function get($value): float
    {
        $validation = $this->validate($value);

        if ($validation->hasErrors()) {
            throw ValidationFailed::fromValidation($validation);
        }

        return filter_var($value, FILTER_VALIDATE_FLOAT);
    }
}

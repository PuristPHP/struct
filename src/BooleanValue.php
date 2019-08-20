<?php
declare(strict_types=1);

namespace Purist\Struct;

final class BooleanValue implements Value
{
    /**
     * @inheritDoc
     */
    public function validate($value): Validation
    {
        if (
            $value !== null
            && filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null
        ) {
            return Validation::successful();
        }

        return Validation::failedValue('BooleanValue', $value);
    }

    /**
     * @inheritDoc
     */
    public function get($value): bool
    {
        $validation = $this->validate($value);

        if ($validation->hasErrors()) {
            throw ValidationFailed::fromValidation($validation);
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}

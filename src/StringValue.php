<?php
declare(strict_types=1);

namespace Purist\Struct;

final class StringValue implements Value
{
    /**
     * @inheritDoc
     */
    public function validate($value): Validation
    {
        if (
            is_string($value)
            || (is_object($value) && method_exists($value, '__toString'))
        ) {
            return Validation::successful();
        }

        return Validation::failedValue('StringValue', $value);
    }

    /**
     * @inheritDoc
     */
    public function get($value): string
    {
        $validation = $this->validate($value);

        if ($validation->hasErrors()) {
            throw ValidationFailed::fromValidation($validation);
        }

        return $value;
    }
}

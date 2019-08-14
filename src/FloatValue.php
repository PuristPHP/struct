<?php
declare(strict_types=1);

namespace Purist\Struct;

final class FloatValue implements Value
{
    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        return is_bool($value) === false
            && filter_var($value, FILTER_VALIDATE_FLOAT) !== false;
    }

    /**
     * @inheritDoc
     */
    public function get($value): float
    {
        if (!$this->validate($value)) {
            throw ValidationFailed::value('float', $value);
        }

        return filter_var($value, FILTER_VALIDATE_FLOAT);
    }
}

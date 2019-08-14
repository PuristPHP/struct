<?php
declare(strict_types=1);

namespace Purist\Struct;

final class BooleanValue implements Value
{
    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        return $value !== null && filter_var(
            $value,
            FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE
        ) !== null;
    }

    /**
     * @inheritDoc
     */
    public function get($value): bool
    {
        if (!$this->validate($value)) {
            throw ValidationFailed::value('boolean', $value);
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}

<?php
declare(strict_types=1);

namespace Purist\Struct;

final class StringValue implements Value
{
    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        return is_string($value)
            || (is_object($value) && method_exists($value, '__toString'));
    }

    /**
     * @inheritDoc
     */
    public function get($value): string
    {
        if (!$this->validate($value)) {
            throw ValidationFailed::value('string', $value);
        }

        return $value;
    }
}

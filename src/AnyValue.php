<?php
declare(strict_types=1);

namespace Purist\Struct;

class AnyValue implements Value
{
    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function get($value)
    {
        return $value;
    }
}

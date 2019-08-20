<?php
declare(strict_types=1);

namespace Purist\Struct;

class AnyValue implements Value
{
    /**
     * @inheritDoc
     */
    public function validate($value): Validation
    {
        return Validation::successful();
    }

    /**
     * @inheritDoc
     */
    public function get($value)
    {
        return $value;
    }
}

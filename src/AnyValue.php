<?php

namespace Purist\Struct;

class AnyValue implements Value
{
    public function validate($value): bool
    {
        return true;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function get($value)
    {
        return $value;
    }
}

<?php

namespace Purist\Struct;

interface Value
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool;

    /**
     * @param mixed $value
     * @return mixed
     * @throws ValidationFailed
     */
    public function get($value);
}

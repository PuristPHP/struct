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
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public function get($value);
}

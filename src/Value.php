<?php

namespace Purist\Struct;

interface Value
{
    /**
     * @param mixed $value
     * @return Validation
     */
    public function validate($value): Validation;

    /**
     * @param mixed $value
     * @return mixed
     * @throws ValidationFailed
     */
    public function get($value);
}

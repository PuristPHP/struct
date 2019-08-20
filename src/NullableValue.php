<?php
declare(strict_types=1);

namespace Purist\Struct;

final class NullableValue implements Value
{
    private $value;

    public function __construct(Value $value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): Validation
    {
        if ($value === null || $value === '') {
            return Validation::successful();
        }

        return $this->value->validate($value);
    }

    /**
     * @inheritDoc
     */
    public function get($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        return $this->value->get($value);
    }
}

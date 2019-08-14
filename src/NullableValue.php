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
    public function validate($values): bool
    {
        return $values === null || $values === '' || $this->value->validate($values);
    }

    /**
     * @inheritDoc
     */
    public function get($values)
    {
        if ($values === null || $values === '') {
            return null;
        }

        return $this->value->get($values);
    }
}

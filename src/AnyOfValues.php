<?php
declare(strict_types=1);

namespace Purist\Struct;

class AnyOfValues implements Value
{
    private $values;

    public function __construct(Value ...$values)
    {
        $this->values = $values;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        foreach($this->values as $valueItem) {
            if ($valueItem->validate($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function get($value)
    {
        foreach ($this->values as $valueItem) {
            if ($valueItem->validate($value)) {
                return $valueItem->get($value);
            }
        }

        throw new ValidationFailed(
            sprintf(
                '%s (%s) is not valid value of: %s',
                $value,
                gettype($value),
                implode(
                    ', ',
                    array_map(
                        function (Value $value) {
                            return get_class($value);
                        },
                        $this->values
                    )
                )
            )
        );
    }
}

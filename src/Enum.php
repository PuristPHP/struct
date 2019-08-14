<?php
declare(strict_types=1);

namespace Purist\Struct;

final class Enum implements Value
{
    private $values;

    public function __construct(array $values)
    {
        if ($values === []) {
            throw new \InvalidArgumentException(
                'The enum definition can not be empty'
            );
        }

        $this->values = $values;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        return in_array($value, $this->values, true);
    }

    /**
     * @inheritDoc
     */
    public function get($value)
    {
        if (!$this->validate($value)) {
            throw ValidationFailed::value('enum', $value);
        }

        return $value;
    }
}

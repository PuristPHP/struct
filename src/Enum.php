<?php
declare(strict_types=1);

namespace Purist\Struct;

final class Enum implements Value
{
    private $values;

    /**
     * @throws \InvalidArgumentException
     */
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
    public function validate($value): Validation
    {
        if (in_array($value, $this->values, true)) {
            return Validation::successful();
        }

        return Validation::failedValue('Enum', $value);
    }

    /**
     * @inheritDoc
     */
    public function get($value)
    {
        $validation = $this->validate($value);

        if ($validation->hasErrors()) {
            throw ValidationFailed::fromValidation($validation);
        }

        return $value;
    }
}

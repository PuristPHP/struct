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
    public function validate($value): Validation
    {
        foreach ($this->values as $valueItem) {
            if (!$valueItem->validate($value)->hasErrors()) {
                return Validation::successful();
            }
        }

        $errors = array_reduce(
            $this->values,
            function (array $errors, Value $valueItem) use ($value) {
                foreach ($valueItem->validate($value)->errors() as $error) {
                    $errors[] = $error;
                }

                return $errors;
            },
            []
        );

        return Validation::failedWithErrors(
            '[AnyOfValues] validation failed:',
            ...$errors
        );
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

        foreach ($this->values as $valueItem) {
            if (!$valueItem->validate($value)->hasErrors()) {
                return $valueItem->get($value);
            }
        }
    }
}

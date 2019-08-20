<?php
declare(strict_types=1);

namespace Purist\Struct;

class IndexedArray implements Value
{
    private $value;

    public function __construct(?Value $value = null)
    {
        $this->value = $value ?? new AnyValue();
    }

    /**
     * @inheritDoc
     */
    public function validate($values): Validation
    {
        if ($values instanceof \stdClass) {
            $values = (array) $values;
        }

        if (!is_array($values)) {
            return Validation::failedValue('IndexedArrayValue', $values);
        }

        $validKeys = array_filter(array_map('is_int', array_keys($values)));

        if (count($validKeys) !== count($values)) {
            return Validation::failedValue('IndexedArrayValue', $values);
        }

        foreach ($values as $value) {
            $validation = $this->value->validate($value);

            if ($validation->hasErrors()) {
                return Validation::failedValue('IndexedArrayValue', $values, $validation);
            }
        }

        return Validation::successful();
    }

    /**
     * @inheritDoc
     */
    public function get($values): array
    {
        if ($values instanceof \stdClass) {
            $values = (array) $values;
        }

        $validation = $this->validate($values);

        if ($validation->hasErrors()) {
            throw ValidationFailed::fromValidation($validation);
        }

        return array_values(
            array_map(
                function ($value) {
                    return $this->value->get($value);
                },
                $values
            )
        );
    }
}

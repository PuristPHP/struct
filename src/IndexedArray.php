<?php

namespace Purist\Struct;

class IndexedArray implements Value
{
    private $value;

    public function __construct(?Value $value = null)
    {
        $this->value = $value ?? new AnyValue();
    }

    public function validate($input): bool
    {
        if ($input instanceof \stdClass) {
            $input = (array) $input;
        }

        if (
            !is_array($input)
            || (
                $input !== []
                && array_keys($input) !== range(0, count($input) - 1)
            )
        ) {
            return false;
        }

        foreach ($input as $value) {
            if (!$this->value->validate($value)) {
                return false;
            }
        }

        return true;
    }

    public function get($input): array
    {
        if ($input instanceof \stdClass) {
            $input = (array) $input;
        }

        if (!is_array($input)) {
            throw new \InvalidArgumentException(
                sprintf('An array was expected but %s was passed', gettype($input))
            );
        }

        $validKeys = (new IndexedArray(new IntegerValue()))->validate(array_keys($input));

        if (!$validKeys) {
            throw new \InvalidArgumentException('The passed array was not indexed.');
        }

        return array_map(
            function($value) {
                return $this->value->get($value);
            },
            array_values($input)
        );
    }
}

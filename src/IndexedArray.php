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
    public function validate($values): bool
    {
        if ($values instanceof \stdClass) {
            $values = (array) $values;
        }

        if (!is_array($values)) {
            return false;
        }

        $validKeys = array_filter(array_map('is_int', array_keys($values)));

        if (count($validKeys) !== count($values)) {
            return false;
        }

        foreach ($values as $value) {
            if (!$this->value->validate($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function get($values): array
    {
        if ($values instanceof \stdClass) {
            $values = (array) $values;
        }

        if (!$this->validate($values)) {
            throw ValidationFailed::value('indexed array', $values);
        }

        return array_values(array_map(
            function($value) {
                return $this->value->get($value);
            },
            $values
        ));
    }
}

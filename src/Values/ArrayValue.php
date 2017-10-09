<?php
declare(strict_types=1);

namespace Purist\Struct\Values;

final class ArrayValue implements Value
{
    private $struct;

    /**
     * @param Value[] $struct Keys are struct parameters
     */
    public function __construct(array $struct)
    {
        $this->struct = $struct;
    }

    public function validate($values): bool
    {
        if (!is_array($values)) {
            return false;
        }

        /** @var Value $value */
        foreach ($this->struct as $key => $value) {
            if ($value->validate($values[$key] ?? null)) {
                continue;
            }

            return false;
        }

        return true;
    }

    public function get($values): array
    {
        if (!is_array($values)) {
            throw new \InvalidArgumentException(
                sprintf('An array was expected but %s was passed', gettype($values))
            );
        }

        return array_reduce(
            array_keys($this->struct),
            function(array $carry, string $key) use ($values) {
                if (array_key_exists($key, $values)) {
                    $carry[$key] = $this->struct[$key]->get($values[$key]);
                }

                return $carry;
            },
            []
        );
    }
}

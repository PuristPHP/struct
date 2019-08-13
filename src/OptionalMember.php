<?php

namespace Purist\Struct;

class OptionalMember implements Member
{
    private $name;
    private $value;

    public function __construct(string $name, Value $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function get(array $input): array
    {
        if (!array_key_exists($this->name, $input)) {
            return [$this->name => null];
        }

        return [$this->name => $this->value->get($input[$this->name])];
    }

    public function valid(array $input): bool
    {
        return !array_key_exists($this->name, $input)
            || $this->value->validate($input[$this->name]);
    }
}

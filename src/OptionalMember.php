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

    /**
     * @inheritDoc
     */
    public function valid(array $input): bool
    {
        return !array_key_exists($this->name, $input)
            || $this->value->validate($input[$this->name]);
    }

    /**
     * @inheritDoc
     */
    public function get(array $input): array
    {
        if (!array_key_exists($this->name, $input)) {
            return [$this->name => null];
        }

        try {
            return [$this->name => $this->value->get($input[$this->name])];
        } catch (ValidationFailed $e) {
            throw ValidationFailed::member($this->name, 'optional', $e);
        }
    }
}

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
    public function validate(array $input): Validation
    {
        if (!array_key_exists($this->name, $input)) {
            return Validation::successful();
        }

        $validation = $this->value->validate($input[$this->name]);

        if (!$validation->hasErrors()) {
            return Validation::successful();
        }

        return Validation::failedMember('OptionalMember', $this->name, $validation);
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
            throw ValidationFailed::member('OptionalMember', $this->name, $e);
        }
    }

    public function name(): string
    {
        return $this->name;
    }
}

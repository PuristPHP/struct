<?php
declare(strict_types=1);

namespace Purist\Struct;

final class RequiredMember implements Member
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
            return Validation::failed(
                sprintf('[RequiredMember] %s was missing from input array.', $this->name)
            );
        }

        $validation = $this->value->validate($input[$this->name]);

        if ($validation->hasErrors()) {
            return Validation::failedMember('RequiredMember', $this->name, $validation);
        }

        return Validation::successful();
    }

    /**
     * @inheritDoc
     */
    public function get(array $input): array
    {
        if (!array_key_exists($this->name, $input)) {
            throw new ValidationFailed(
                sprintf('[RequiredMember] %s was missing from input array.', $this->name)
            );
        }

        try {
            return [$this->name => $this->value->get($input[$this->name])];
        } catch (ValidationFailed $e) {
            throw ValidationFailed::member('RequiredMember', $this->name, $e);
        }
    }

    public function name(): string
    {
        return $this->name;
    }
}

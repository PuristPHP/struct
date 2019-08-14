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
    public function valid(array $input): bool
    {
        return array_key_exists($this->name, $input)
            && $this->value->validate($input[$this->name]);
    }

    /**
     * @inheritDoc
     */
    public function get(array $input): array
    {
        if (!array_key_exists($this->name, $input)) {
            throw new ValidationFailed(
                "{$this->name} was missing from input array."
            );
        }

        try {
            return [$this->name => $this->value->get($input[$this->name])];
        } catch (ValidationFailed $e) {
            throw ValidationFailed::member($this->name, 'required', $e);
        }
    }
}

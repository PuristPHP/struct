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

    public function get(array $input): array
    {
        if (!array_key_exists($this->name, $input)) {
            throw new \InvalidArgumentException(
                "{$this->name} was missing from input array."
            );
        }

        return [$this->name => $this->value->get($input[$this->name])];
    }

    public function valid(array $input): bool
    {
        return array_key_exists($this->name, $input)
            && $this->value->validate($input[$this->name]);
    }
}

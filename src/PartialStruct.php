<?php
declare(strict_types=1);

namespace Purist\Struct;

final class PartialStruct implements Value
{
    private $members;

    public function __construct(Member ...$members)
    {
        $this->members = $members;
    }

    public function validate($input): bool
    {
        if (!is_array($input)) {
            return false;
        }

        foreach ($this->members as $member) {
            if (!$member->valid($input)) {
                return false;
            }
        }

        return true;
    }

    public function get($input): array
    {
        if (!is_array($input)) {
            throw new \InvalidArgumentException(
                sprintf('An array was expected but %s was passed', gettype($input))
            );
        }

        return array_reduce(
            $this->members,
            function (array $carry, Member $member) use ($input) {
                return array_replace($carry, $member->get($input));
            },
            $input
        );
    }
}

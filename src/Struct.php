<?php
declare(strict_types=1);

namespace Purist\Struct;

final class Struct implements Value
{
    private $members;

    public function __construct(Member ...$members)
    {
        $this->members = $members;
    }

    /**
     * @inheritDoc
     */
    public function validate($value): bool
    {
        if ($value instanceof \stdClass) {
            $value = (array) $value;
        }

        if (!is_array($value)) {
            return false;
        }

        foreach ($this->members as $member) {
            if (!$member->valid($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function get($value): array
    {
        if ($value instanceof \stdClass) {
            $value = (array) $value;
        }

        if (!is_array($value)) {
            throw ValidationFailed::value('struct', $value);
        }

        return array_reduce(
            $this->members,
            function (array $carry, Member $member) use ($value) {
                return $carry + $member->get($value);
            },
            []
        );
    }
}

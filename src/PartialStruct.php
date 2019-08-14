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

        foreach ($this->members as $member) {
            if (!$member->valid($values)) {
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

        if (!is_array($values)) {
            throw ValidationFailed::value('partial struct', $values);
        }

        return array_reduce(
            $this->members,
            function (array $carry, Member $member) use ($values) {
                return array_replace($carry, $member->get($values));
            },
            $values
        );
    }
}

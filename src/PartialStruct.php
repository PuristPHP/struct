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
    public function validate($values): Validation
    {
        if ($values instanceof \stdClass) {
            $values = (array) $values;
        }

        if (!is_array($values)) {
            return Validation::failed(
                sprintf('[PartialStruct] input need to be array, %s passed', gettype($values))
            );
        }

        return Validation::validateMembers(
            'PartialStruct',
            $values,
            ...$this->members
        );
    }

    /**
     * @inheritDoc
     */
    public function get($values): array
    {
        if ($values instanceof \stdClass) {
            $values = (array) $values;
        }

        $validation = $this->validate($values);

        if ($validation->hasErrors()) {
            throw ValidationFailed::fromValidation($validation);
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

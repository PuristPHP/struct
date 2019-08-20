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
    public function validate($values): Validation
    {
        if ($values instanceof \stdClass) {
            $values = (array) $values;
        }

        if (!is_array($values)) {
            return Validation::failed(
                sprintf(
                    '[Struct] input need to be array, %s passed',
                    gettype($values)
                )
            );
        }

        return Validation::validateMembers(
            'Struct',
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
            static function (array $carry, Member $member) use ($values) {
                return $carry + $member->get($values);
            },
            []
        );
    }
}

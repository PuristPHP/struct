<?php
declare(strict_types=1);

namespace Purist\Struct;

interface Member
{
    public function name(): string;

    public function validate(array $input): Validation;

    /**
     * @throws ValidationFailed
     */
    public function get(array $input): array;
}

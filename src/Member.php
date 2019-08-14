<?php
declare(strict_types=1);

namespace Purist\Struct;

interface Member
{
    public function valid(array $input): bool;

    /**
     * @throws ValidationFailed
     */
    public function get(array $input): array;
}

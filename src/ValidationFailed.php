<?php

declare(strict_types=1);

namespace Purist\Struct;

use Purist\Struct\Constraint\Constraint;

final class ValidationFailed extends \Exception
{
    public function __construct(string $type, $value, Constraint ...$constraints)
    {
        parent::__construct(
            implode(
                PHP_EOL,
                array_reduce(
                    $constraints,
                    function (array $messages, Constraint $constraint) use ($value) {
                        if (!$constraint->validate($value)) {
                            $messages[] = sprintf(
                                'Constraint %s failed for: %s',
                                $constraint,
                                $value
                            );
                        }

                        return $messages;
                    },
                    [
                        sprintf(
                            'Validation %s failed for: %s (%s)',
                            $type,
                            var_export($value, true),
                            gettype($value)
                        ),
                    ]
                )
            )
        );
    }
}

<?php

declare(strict_types=1);

namespace Purist\Struct;

use Purist\Struct\Constraint\Constraint;

final class ValidationFailed extends \Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        if ($previous !== null) {
            $message .= PHP_EOL . $previous->getMessage();
        }

        parent::__construct($message, $code, $previous);
    }

    public static function member(
        string $name,
        string $type,
        ?\Throwable $previous = null
    ): self {
        return new self(
            sprintf('Validation failed for %s member: %s', $type, $name),
            0,
            $previous
        );
    }

    public static function value(
        string $type,
        $value,
        ?\Throwable $previous = null,
        Constraint ...$constraints
    ): self {
        return new self(
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
            ),
            0,
            $previous
        );
    }
}

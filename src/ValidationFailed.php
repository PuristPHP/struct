<?php

declare(strict_types=1);

namespace Purist\Struct;

use Purist\Struct\Constraint\Constraint;

final class ValidationFailed extends \Exception
{
    private $errors = [];

    public function __construct(
        string $message = '',
        ?\Throwable $previous = null
    ) {
        $this->errors[] = $message;

        if ($previous instanceof self) {
            $this->errors[] = $previous->getMessage();
        }

        parent::__construct($message, 0, $previous);
    }

    public static function fromValidation(
        Validation $validation,
        ?\Throwable $previous = null
    ): self {
        return new self(
            implode(PHP_EOL, $validation->errors()),
            $previous,
        );
    }

    public static function member(
        string $type,
        string $name,
        ?\Throwable $previous = null
    ): self {
        return new self(
            sprintf('[%s] %s validation failed.', $type, $name),
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
                                'Constraint %s failed for value: %s',
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
            $previous
        );
    }

    public function errors(): array
    {
        return $this->errors;
    }
}

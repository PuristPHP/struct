<?php
declare(strict_types=1);

namespace Purist\Struct;

use Purist\Struct\Constraint\Constraint;

final class Validation
{
    private $previousValidation;
    private $error;

    private function __construct(?string $error = null, ?Validation $previousValidation = null)
    {
        $this->error = $error;
        $this->previousValidation = $previousValidation;
    }

    public static function failedValue(
        string $name,
        $value,
        ?Validation $previous = null,
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
                                '* Constraint %s failed for value: %s',
                                $constraint,
                                $value
                            );
                        }

                        return $messages;
                    },
                    [
                        sprintf(
                            '[%s] validation failed for: %s (%s)',
                            $name,
                            var_export($value, true),
                            gettype($value)
                        ),
                    ]
                )
            ),
            $previous
        );
    }

    public static function failedMember(
        string $name,
        string $memberName,
        ?Validation $previous = null
    ): self {
        return new self(
            sprintf('[%s] validation failed for: %s', $name, $memberName),
            $previous
        );
    }

    public static function validateMembers(string $name, $values, Member ...$members): self
    {
        $errors = array_reduce(
            $members,
            function (array $errors, Member $member) use ($values) {
                foreach ($member->validate($values)->errors() as $error) {
                    $errors[] = $error;
                }

                return $errors;
            },
            []
        );

        return $errors === []
            ? self::successful()
            : self::failedWithErrors(
                sprintf('[%s] validation failed:', $name),
                ...$errors
            );
    }

    public static function failedWithErrors(string $error, string ...$errors): self
    {
        return new self(
            implode(
                PHP_EOL,
                array_merge(
                    [$error],
                    $errors
                )
            )
        );
    }

    public static function failed(string $error, ?Validation $previousValidation = null): self
    {
        return new self(
            $error,
            $previousValidation
        );
    }

    public static function successful(): self
    {
        return new self();
    }

    public function hasErrors(): bool
    {
        return count($this->errors()) > 0;
    }

    public function errors(): array
    {
        if ($this->error === null) {
            return [];
        }

        if ($this->previousValidation === null) {
            return [$this->error];
        }

        return array_merge(
            [$this->error],
            $this->previousValidation->errors()
        );
    }
}

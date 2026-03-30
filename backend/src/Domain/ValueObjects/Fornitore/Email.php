<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\Fornitore;

use InvalidArgumentException;

final readonly class Email
{
    public function __construct(public string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email non valida: {$value}");
        }
        if (mb_strlen($value) > 100) {
            throw new InvalidArgumentException('L\'email non può superare 100 caratteri.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

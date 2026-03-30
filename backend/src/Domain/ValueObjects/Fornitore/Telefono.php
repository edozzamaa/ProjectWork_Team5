<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\Fornitore;

use InvalidArgumentException;

final readonly class Telefono
{
    public string $value;

    public function __construct(string $value)
    {
        $cleaned = preg_replace('/[\s\-\.\/]/', '', $value);
        if (!preg_match('/^\+?\d{6,15}$/', $cleaned)) {
            throw new InvalidArgumentException("Numero di telefono non valido: '{$value}'. Deve contenere tra 6 e 15 cifre.");
        }
        $this->value = $cleaned;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

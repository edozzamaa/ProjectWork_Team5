<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\Fornitore;

use src\Domain\Exceptions\InvalidFormatException;

final readonly class PartitaIVA
{
    public string $value;

    public function __construct(string $value)
    {
        $cleaned = preg_replace('/\s+/', '', $value);
        if (!preg_match('/^\d{11}$/', $cleaned)) {
            throw new InvalidFormatException('Partita IVA non valida: deve essere composta da 11 cifre numeriche.');
        }
        $this->value = $cleaned;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

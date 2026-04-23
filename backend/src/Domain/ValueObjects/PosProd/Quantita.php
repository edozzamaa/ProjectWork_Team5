<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\PosProd;

use src\Domain\Exceptions\NegativeQuantityException;

final readonly class Quantita
{
    public function __construct(public int $value)
    {
        if ($value < 0) {
            throw new NegativeQuantityException("La quantità non può essere negativa (valore: {$value}).");
        }
    }

    public function aggiungi(int $qta): self
    {
        return new self($this->value + $qta);
    }

    public function sottrai(int $qta): self
    {
        return new self($this->value - $qta);
    }

    public function isZero(): bool
    {
        return $this->value === 0;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}

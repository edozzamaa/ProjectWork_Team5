<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\Prodotto;

use src\Domain\Exceptions\NegativeQuantityException;

final readonly class QuantitaRiordino
{
    public function __construct(public int $value)
    {
        if ($value < 0) {
            throw new NegativeQuantityException("La quantità di riordino non può essere negativa (valore: {$value}).");
        }
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}

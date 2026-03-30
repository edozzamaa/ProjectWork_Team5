<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\CodificaOE;

use InvalidArgumentException;

final readonly class CodificaOEDescrizione
{
    public function __construct(public string $value)
    {
        if (strlen(trim($value)) === 0) {
            throw new InvalidArgumentException('La descrizione codifica OE non può essere vuota.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

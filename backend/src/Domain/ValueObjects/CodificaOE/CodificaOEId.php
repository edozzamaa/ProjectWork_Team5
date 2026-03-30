<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\CodificaOE;

use InvalidArgumentException;

final readonly class CodificaOEId
{
    public function __construct(public string $value)
    {
        if (strlen(trim($value)) === 0) {
            throw new InvalidArgumentException('Il codice OE non può essere vuoto.');
        }
        if (mb_strlen($value) > 50) {
            throw new InvalidArgumentException('Il codice OE non può superare 50 caratteri.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

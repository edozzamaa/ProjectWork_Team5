<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\CodificaReg;

use InvalidArgumentException;

final readonly class CodificaRegId
{
    public function __construct(public string $value)
    {
        if (strlen(trim($value)) === 0) {
            throw new InvalidArgumentException('Il codice regionale non può essere vuoto.');
        }
        if (mb_strlen($value) > 50) {
            throw new InvalidArgumentException('Il codice regionale non può superare 50 caratteri.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

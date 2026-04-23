<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\CodificaReg;

use src\Domain\Exceptions\RequiredValueException;

final readonly class CodificaRegDescrizione
{
    public function __construct(public string $value)
    {
        if (strlen(trim($value)) === 0) {
            throw new RequiredValueException('La descrizione codifica regionale non può essere vuota.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

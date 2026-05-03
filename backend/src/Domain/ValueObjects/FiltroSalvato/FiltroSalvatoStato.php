<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\FiltroSalvato;

use src\Domain\Exceptions\RequiredValueException;

final readonly class FiltroSalvatoStato
{
    public function __construct(public string $value)
    {
        if (strlen(trim($value)) === 0) {
            throw new RequiredValueException('Lo stato del filtro non può essere vuoto.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

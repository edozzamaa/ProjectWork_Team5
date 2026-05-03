<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\FiltroSalvato;

use src\Domain\Exceptions\RequiredValueException;

final readonly class FiltroSalvatoId
{
    public function __construct(public int $value)
    {
        if ($value < 0) {
            throw new RequiredValueException('L\'id del filtro non può essere negativo.');
        }
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}

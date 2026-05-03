<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\FiltroSalvato;

use src\Domain\Exceptions\MaxLengthExceededException;
use src\Domain\Exceptions\RequiredValueException;

final readonly class FiltroSalvatoNome
{
    public function __construct(public string $value)
    {
        if (strlen(trim($value)) === 0) {
            throw new RequiredValueException('Il nome del filtro non può essere vuoto.');
        }
        if (mb_strlen($value) > 100) {
            throw new MaxLengthExceededException('Il nome del filtro non può superare 100 caratteri.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

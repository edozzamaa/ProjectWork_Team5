<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\Armadio;

use src\Domain\Exceptions\MaxLengthExceededException;

final readonly class ArmadioDescrizione
{
    public function __construct(public string $value)
    {
        if (mb_strlen($value) > 100) {
            throw new MaxLengthExceededException('La descrizione armadio non può superare 100 caratteri.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

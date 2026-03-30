<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\Posizione;

use InvalidArgumentException;

final readonly class PosizioneDescrizione
{
    public function __construct(public string $value)
    {
        if (mb_strlen($value) > 500) {
            throw new InvalidArgumentException('La descrizione posizione non può superare 500 caratteri.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

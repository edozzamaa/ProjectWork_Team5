<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\Fornitore;

use InvalidArgumentException;

final readonly class Indirizzo
{
    public function __construct(public string $value)
    {
        if (mb_strlen($value) > 255) {
            throw new InvalidArgumentException('L\'indirizzo non può superare 255 caratteri.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

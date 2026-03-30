<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\AttrProd;

use InvalidArgumentException;

final readonly class ValoreAttributo
{
    public function __construct(public string $value)
    {
        if (mb_strlen($value) > 100) {
            throw new InvalidArgumentException('Il valore attributo non può superare 100 caratteri.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

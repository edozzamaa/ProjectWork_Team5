<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\Fornitore;

use InvalidArgumentException;

final readonly class FornitoreId
{
    public function __construct(public string $value)
    {
        if (strlen(trim($value)) === 0) {
            throw new InvalidArgumentException('La ragione sociale non può essere vuota.');
        }
        if (mb_strlen($value) > 100) {
            throw new InvalidArgumentException('La ragione sociale non può superare 100 caratteri.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

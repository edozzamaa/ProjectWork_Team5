<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\Fornitore;

use src\Domain\Exceptions\MaxLengthExceededException;
use src\Domain\Exceptions\RequiredValueException;

final readonly class FornitoreId
{
    public function __construct(public string $value)
    {
        if (strlen(trim($value)) === 0) {
            throw new RequiredValueException('La ragione sociale non può essere vuota.');
        }
        if (mb_strlen($value) > 100) {
            throw new MaxLengthExceededException('La ragione sociale non può superare 100 caratteri.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

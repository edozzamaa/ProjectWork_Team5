<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\Attributo;

use src\Domain\Exceptions\MaxLengthExceededException;
use src\Domain\Exceptions\RequiredValueException;

final readonly class AttributoNome
{
    public function __construct(public string $value)
    {
        if (strlen(trim($value)) === 0) {
            throw new RequiredValueException('Il nome attributo non può essere vuoto.');
        }
        if (mb_strlen($value) > 50) {
            throw new MaxLengthExceededException('Il nome attributo non può superare 50 caratteri.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

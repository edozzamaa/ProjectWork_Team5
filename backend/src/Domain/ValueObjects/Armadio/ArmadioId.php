<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\Armadio;

use InvalidArgumentException;

final readonly class ArmadioId
{
    public function __construct(public string $value)
    {
        if (strlen(trim($value)) === 0) {
            throw new InvalidArgumentException('Il codice armadio non può essere vuoto.');
        }
        if (mb_strlen($value) > 10) {
            throw new InvalidArgumentException('Il codice armadio non può superare 10 caratteri.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\Attributo;

use InvalidArgumentException;

final readonly class AttributoNome
{
    public function __construct(public string $value)
    {
        if (strlen(trim($value)) === 0) {
            throw new InvalidArgumentException('Il nome attributo non può essere vuoto.');
        }
        if (mb_strlen($value) > 50) {
            throw new InvalidArgumentException('Il nome attributo non può superare 50 caratteri.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

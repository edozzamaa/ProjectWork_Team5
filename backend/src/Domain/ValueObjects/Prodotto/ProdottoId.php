<?php declare(strict_types=1);

namespace src\Domain\ValueObjects\Prodotto;

use InvalidArgumentException;

final readonly class ProdottoId
{
    public function __construct(public string $value)
    {
        if (strlen(trim($value)) === 0) {
            throw new InvalidArgumentException('Il codice prodotto non può essere vuoto.');
        }
        if (mb_strlen($value) > 20) {
            throw new InvalidArgumentException('Il codice prodotto non può superare 20 caratteri.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}

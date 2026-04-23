<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class AssignAttributoToProdottoInput {
    public function __construct(
        public string $codProd,
        public string $codAttr,
        public ?string $valore = null
    ) {}
}

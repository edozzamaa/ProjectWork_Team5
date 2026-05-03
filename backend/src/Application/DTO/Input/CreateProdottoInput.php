<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class CreateProdottoInput {
    public function __construct(
        public int $qtaRiordino = 0,
        public ?string $codCat = null,
        public ?string $codReg = null,
        public ?string $codOE = null
    ) {}
}

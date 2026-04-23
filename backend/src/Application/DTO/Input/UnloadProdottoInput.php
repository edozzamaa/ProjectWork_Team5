<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class UnloadProdottoInput {
    public function __construct(
        public string $codProd,
        public string $codArmadio,
        public string $codScaffale,
        public int $qta
    ) {}
}

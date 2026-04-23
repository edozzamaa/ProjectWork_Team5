<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class LoadProdottoInput {
    /**
     * @param array<string, string> $attributi
     */
    public function __construct(
        public string $codProd,
        public string $codArmadio,
        public string $codScaffale,
        public int $qta,
        public array $attributi = []
    ) {}
}

<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class GetAttributiProdottoInput {
    public function __construct(
        public string $codProd
    ) {}
}

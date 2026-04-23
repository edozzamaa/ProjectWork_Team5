<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class UpdateProdottoInput {
    /**
     * @param array<string, mixed> $fields
     */
    public function __construct(
        public string $codProd,
        public array $fields
    ) {}
}

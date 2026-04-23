<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class CreateCategoriaInput {
    public function __construct(
        public string $codCat,
        public string $tipo
    ) {}
}

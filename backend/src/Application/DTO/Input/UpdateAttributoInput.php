<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class UpdateAttributoInput {
    public function __construct(
        public string $codAttr,
        public string $nome
    ) {}
}

<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class CreateCodificaOEInput {
    public function __construct(
        public string $codOE,
        public string $descrizione,
        public ?string $ragSoc = null
    ) {}
}

<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class CreateFornitoreInput {
    public function __construct(
        public string $ragSoc,
        public ?string $partIVA = null,
        public ?string $telefono = null,
        public ?string $indirizzo = null,
        public ?string $email = null
    ) {}
}

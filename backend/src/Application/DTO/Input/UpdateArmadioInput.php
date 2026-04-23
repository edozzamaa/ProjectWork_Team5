<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class UpdateArmadioInput {
    public function __construct(
        public string $codArmadio,
        public ?string $descrizione
    ) {}
}

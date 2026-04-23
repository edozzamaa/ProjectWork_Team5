<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class UpdateCodificaRegInput {
    public function __construct(
        public string $codReg,
        public string $descrizione
    ) {}
}

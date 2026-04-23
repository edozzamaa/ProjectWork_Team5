<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class CreateCodificaRegInput {
    public function __construct(
        public string $codReg,
        public string $descrizione
    ) {}
}

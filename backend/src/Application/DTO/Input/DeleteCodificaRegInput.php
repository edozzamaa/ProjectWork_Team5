<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class DeleteCodificaRegInput {
    public function __construct(
        public string $codReg
    ) {}
}

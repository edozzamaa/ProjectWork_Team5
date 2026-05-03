<?php declare(strict_types=1);

namespace src\Application\DTO\Input;

final readonly class CreateFiltroSalvatoInput
{
    public function __construct(
        public string $nome,
        public string $stato
    ) {}
}

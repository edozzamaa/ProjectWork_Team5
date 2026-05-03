<?php declare(strict_types=1);

namespace src\Application\DTO\Output;

class FiltroSalvatoDTO
{
    public readonly int $id;
    public readonly string $nome;
    public readonly mixed $stato;

    public function __construct(int $id, string $nome, mixed $stato)
    {
        $this->id    = $id;
        $this->nome  = $nome;
        $this->stato = $stato;
    }
}

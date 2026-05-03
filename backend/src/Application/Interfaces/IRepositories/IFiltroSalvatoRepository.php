<?php declare(strict_types=1);

namespace src\Application\Interfaces\IRepositories;

use src\Domain\Models\FiltroSalvato;
use src\Domain\ValueObjects\FiltroSalvato\FiltroSalvatoId;

interface IFiltroSalvatoRepository
{
    /** @return FiltroSalvato[] */
    public function findAll(): array;

    public function findById(FiltroSalvatoId $id): ?FiltroSalvato;

    public function save(FiltroSalvato $filtro): FiltroSalvatoId;

    public function delete(FiltroSalvatoId $id): void;
}

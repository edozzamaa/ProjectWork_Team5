<?php declare(strict_types=1);

namespace src\Application\Interfaces\IServices;

use src\Application\DTO\Output\FiltroSalvatoDTO;
use src\Application\DTO\Input\CreateFiltroSalvatoInput;
use src\Application\DTO\Input\DeleteFiltroSalvatoInput;

interface IFiltroSalvatoService
{
    /** @return FiltroSalvatoDTO[] */
    public function getAll(): array;

    public function create(CreateFiltroSalvatoInput $input): FiltroSalvatoDTO;

    public function delete(DeleteFiltroSalvatoInput $input): void;
}

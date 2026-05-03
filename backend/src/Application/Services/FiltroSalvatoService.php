<?php declare(strict_types=1);

namespace src\Application\Services;

use src\Application\Interfaces\IServices\IFiltroSalvatoService;
use src\Application\Interfaces\IRepositories\IFiltroSalvatoRepository;
use src\Application\DTO\Input\CreateFiltroSalvatoInput;
use src\Application\DTO\Input\DeleteFiltroSalvatoInput;
use src\Application\DTO\Output\FiltroSalvatoDTO;
use src\Domain\Models\FiltroSalvato;
use src\Domain\ValueObjects\FiltroSalvato\FiltroSalvatoId;
use src\Domain\ValueObjects\FiltroSalvato\FiltroSalvatoNome;
use src\Domain\ValueObjects\FiltroSalvato\FiltroSalvatoStato;

class FiltroSalvatoService implements IFiltroSalvatoService
{
    public function __construct(
        private IFiltroSalvatoRepository $filtroRepository
    ) {}

    private function toDTO(FiltroSalvato $filtro): FiltroSalvatoDTO
    {
        return new FiltroSalvatoDTO(
            $filtro->getId()->value,
            $filtro->getNome()->value,
            json_decode($filtro->getStato()->value, true)
        );
    }

    /** @return FiltroSalvatoDTO[] */
    public function getAll(): array
    {
        return array_map(fn(FiltroSalvato $f) => $this->toDTO($f), $this->filtroRepository->findAll());
    }

    public function create(CreateFiltroSalvatoInput $input): FiltroSalvatoDTO
    {
        $filtro = new FiltroSalvato(
            new FiltroSalvatoId(0),
            new FiltroSalvatoNome($input->nome),
            new FiltroSalvatoStato($input->stato)
        );
        $newId = $this->filtroRepository->save($filtro);
        $saved = $this->filtroRepository->findById($newId);
        return $this->toDTO($saved);
    }

    public function delete(DeleteFiltroSalvatoInput $input): void
    {
        if ($this->filtroRepository->findById(new FiltroSalvatoId($input->id)) === null) {
            throw new \RuntimeException("Filtro '{$input->id}' non trovato.");
        }
        $this->filtroRepository->delete(new FiltroSalvatoId($input->id));
    }
}

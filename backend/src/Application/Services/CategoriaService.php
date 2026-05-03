<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Categoria;
use src\Domain\ValueObjects\Categoria\CategoriaId;
use src\Domain\ValueObjects\Categoria\CategoriaTipo;
use src\Application\Interfaces\IServices\ICategoriaService;
use src\Application\DTO\Output\CategoriaDTO;
use src\Application\DTO\Input\GetCategoriaByCodInput;
use src\Application\DTO\Input\CreateCategoriaInput;
use src\Application\DTO\Input\UpdateCategoriaInput;
use src\Application\DTO\Input\DeleteCategoriaInput;
use src\Application\Interfaces\IRepositories\ICategoriaRepository;

class CategoriaService implements ICategoriaService {

    private ICategoriaRepository $categoriaRepository;

    public function __construct(ICategoriaRepository $categoriaRepository) {
        $this->categoriaRepository = $categoriaRepository;
    }

    private function toDTO(Categoria $categoria): CategoriaDTO {
        return new CategoriaDTO(
            (string) $categoria->getCodCat(),
            $categoria->getTipo()->value
        );
    }

    /** @return CategoriaDTO[] */
    public function getAll(): array {
        return array_map(fn(Categoria $c) => $this->toDTO($c), $this->categoriaRepository->findAll());
    }

    public function getByCod(GetCategoriaByCodInput $input): ?CategoriaDTO {
        $categoria = $this->categoriaRepository->findByCod(new CategoriaId($input->codCat));
        return $categoria !== null ? $this->toDTO($categoria) : null;
    }

    public function createCategoria(CreateCategoriaInput $input): void {
        if ($this->categoriaRepository->findByCod(new CategoriaId($input->codCat)) !== null) {
            throw new \RuntimeException("Categoria '{$input->codCat}' già esistente.");
        }
        $categoria = new Categoria(new CategoriaId($input->codCat), new CategoriaTipo($input->tipo));
        $this->categoriaRepository->save($categoria);
    }

    public function updateCategoria(UpdateCategoriaInput $input): void {
        $categoria = $this->categoriaRepository->findByCod(new CategoriaId($input->codCat));
        if ($categoria === null) {
            throw new \RuntimeException("Categoria '{$input->codCat}' non trovata.");
        }
        $categoria->setTipo(new CategoriaTipo($input->tipo));
        $this->categoriaRepository->save($categoria);
    }

    public function deleteCategoria(DeleteCategoriaInput $input): void {
        if ($this->categoriaRepository->findByCod(new CategoriaId($input->codCat)) === null) {
            throw new \RuntimeException("Categoria '{$input->codCat}' non trovata.");
        }
        $this->categoriaRepository->delete(new CategoriaId($input->codCat));
    }
}

<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Categoria;
use src\Domain\ValueObjects\Categoria\CategoriaId;
use src\Domain\ValueObjects\Categoria\CategoriaTipo;
use src\Application\Interfaces\IServices\ICategoriaService;
use src\Application\DTO\CategoriaDTO;
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

    public function getByCod(string $codCat): ?CategoriaDTO {
        $categoria = $this->categoriaRepository->findByCod(new CategoriaId($codCat));
        return $categoria !== null ? $this->toDTO($categoria) : null;
    }

    public function createCategoria(string $codCat, string $tipo): void {
        if ($this->categoriaRepository->findByCod(new CategoriaId($codCat)) !== null) {
            throw new \RuntimeException("Categoria '{$codCat}' già esistente.");
        }
        $categoria = new Categoria(new CategoriaId($codCat), new CategoriaTipo($tipo));
        $this->categoriaRepository->save($categoria);
    }

    public function updateCategoria(string $codCat, string $tipo): void {
        $categoria = $this->categoriaRepository->findByCod(new CategoriaId($codCat));
        if ($categoria === null) {
            throw new \RuntimeException("Categoria '{$codCat}' non trovata.");
        }
        $categoria->setTipo(new CategoriaTipo($tipo));
        $this->categoriaRepository->save($categoria);
    }

    public function deleteCategoria(string $codCat): void {
        if ($this->categoriaRepository->findByCod(new CategoriaId($codCat)) === null) {
            throw new \RuntimeException("Categoria '{$codCat}' non trovata.");
        }
        $this->categoriaRepository->delete(new CategoriaId($codCat));
    }
}

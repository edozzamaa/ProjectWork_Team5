<?php declare(strict_types=1);
namespace src\Application\Services;

use src\Domain\Models\Categoria;
use src\Domain\ValuesObject\ID;
use src\Application\Interfaces\ICategoriaService;
use src\Application\DTO\CategoriaDTO;
use src\Infrastructure\Repositories\CategoriaRepository;

class CategoriaService implements ICategoriaService {

    private CategoriaRepository $categoriaRepository;

    public function __construct(CategoriaRepository $categoriaRepository) {
        $this->categoriaRepository = $categoriaRepository;
    }

    private function toDTO(Categoria $categoria): CategoriaDTO {
        return new CategoriaDTO(
            (string) $categoria->getCodCat(),
            $categoria->getTipo()
        );
    }

    /** @return CategoriaDTO[] */
    public function getAll(): array {
        return array_map(fn(Categoria $c) => $this->toDTO($c), $this->categoriaRepository->findAll());
    }

    public function getByCod(string $codCat): ?CategoriaDTO {
        $categoria = $this->categoriaRepository->findByCod(new ID($codCat));
        return $categoria !== null ? $this->toDTO($categoria) : null;
    }

    public function crea(string $codCat, string $tipo): void {
        if ($this->categoriaRepository->findByCod(new ID($codCat)) !== null) {
            throw new \RuntimeException("Categoria '{$codCat}' già esistente.");
        }
        $categoria = new Categoria(new ID($codCat), $tipo);
        $this->categoriaRepository->save($categoria);
    }

    public function aggiorna(string $codCat, string $tipo): void {
        $categoria = $this->categoriaRepository->findByCod(new ID($codCat));
        if ($categoria === null) {
            throw new \RuntimeException("Categoria '{$codCat}' non trovata.");
        }
        $categoria->setTipo($tipo);
        $this->categoriaRepository->save($categoria);
    }

    public function elimina(string $codCat): void {
        if ($this->categoriaRepository->findByCod(new ID($codCat)) === null) {
            throw new \RuntimeException("Categoria '{$codCat}' non trovata.");
        }
        $this->categoriaRepository->delete(new ID($codCat));
    }
}

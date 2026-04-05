<?php declare(strict_types=1);
namespace src\Application\Interfaces\IServices;

use src\Application\DTO\CategoriaDTO;

interface ICategoriaService {

    /** @return CategoriaDTO[] */
    public function getAll(): array;

    public function getByCod(string $codCat): ?CategoriaDTO;

    public function createCategoria(string $codCat, string $tipo): void;

    public function updateCategoria(string $codCat, string $tipo): void;

    public function deleteCategoria(string $codCat): void;
}

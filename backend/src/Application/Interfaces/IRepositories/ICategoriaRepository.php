<?php declare(strict_types=1);
namespace src\Application\Interfaces\IRepositories;

use src\Domain\Models\Categoria;
use src\Domain\ValueObjects\Categoria\CategoriaId;

interface ICategoriaRepository {

    public function findByCod(CategoriaId $codCat): ?Categoria;

    /** @return Categoria[] */
    public function findAll(): array;

    public function save(Categoria $categoria): void;

    public function delete(CategoriaId $codCat): void;
}

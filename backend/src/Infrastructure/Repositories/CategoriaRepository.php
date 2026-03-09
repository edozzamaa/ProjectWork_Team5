<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\Categoria;
use src\Domain\ValuesObject\ID;

interface CategoriaRepository {

    public function findByCod(ID $codCat): ?Categoria;

    /** @return Categoria[] */
    public function findAll(): array;

    public function save(Categoria $categoria): void;

    public function delete(ID $codCat): void;
}

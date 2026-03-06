<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\Categoria;

interface CategoriaRepository {

    public function findByCod(string $codCat): ?Categoria;

    /** @return Categoria[] */
    public function findAll(): array;

    public function save(Categoria $categoria): void;

    public function delete(string $codCat): void;
}

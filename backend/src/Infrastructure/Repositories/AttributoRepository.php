<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\Attributo;

interface AttributoRepository {

    public function findByCod(string $codAttr): ?Attributo;

    /** @return Attributo[] */
    public function findAll(): array;

    public function save(Attributo $attributo): void;

    public function delete(string $codAttr): void;
}

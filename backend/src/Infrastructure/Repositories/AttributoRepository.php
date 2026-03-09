<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\Attributo;
use src\Domain\ValuesObject\ID;

interface AttributoRepository {

    public function findByCod(ID $codAttr): ?Attributo;

    /** @return Attributo[] */
    public function findAll(): array;

    public function save(Attributo $attributo): void;

    public function delete(ID $codAttr): void;
}

<?php declare(strict_types=1);
namespace src\Application\Interfaces\IRepositories;

use src\Domain\Models\Attributo;
use src\Domain\ValueObjects\Attributo\AttributoId;

interface IAttributoRepository {

    public function findByCod(AttributoId $codAttr): ?Attributo;

    /** @return Attributo[] */
    public function findAll(): array;

    public function save(Attributo $attributo): void;

    public function delete(AttributoId $codAttr): void;
}

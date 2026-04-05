<?php declare(strict_types=1);
namespace src\Application\Interfaces\IRepositories;

use src\Domain\Models\Fornitore;
use src\Domain\ValueObjects\Fornitore\FornitoreId;

interface IFornitoreRepository {

    public function findByRagSoc(FornitoreId $ragSoc): ?Fornitore;

    /** @return Fornitore[] */
    public function findAll(): array;

    public function save(Fornitore $fornitore): void;

    /** @param string[] $columns */
    public function update(Fornitore $fornitore, array $columns): void;

    public function delete(FornitoreId $ragSoc): void;
}

<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\Fornitore;
use src\Domain\ValuesObject\ID;

interface FornitoreRepository {

    public function findByRagSoc(ID $ragSoc): ?Fornitore;

    /** @return Fornitore[] */
    public function findAll(): array;

    public function save(Fornitore $fornitore): void;

    public function delete(ID $ragSoc): void;
}

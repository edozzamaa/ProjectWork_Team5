<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\Fornitore;

interface FornitoreRepository {

    public function findByRagSoc(string $ragSoc): ?Fornitore;

    /** @return Fornitore[] */
    public function findAll(): array;

    public function save(Fornitore $fornitore): void;

    public function delete(string $ragSoc): void;
}

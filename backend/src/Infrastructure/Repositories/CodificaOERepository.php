<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\CodificaOE;

interface CodificaOERepository {

    public function findByCod(string $codOE): ?CodificaOE;

    /** @return CodificaOE[] */
    public function findAll(): array;

    /** @return CodificaOE[] */
    public function findByFornitore(string $ragSoc): array;

    public function save(CodificaOE $codifica): void;

    public function delete(string $codOE): void;
}

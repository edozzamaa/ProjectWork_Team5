<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\CodificaOE;
use src\Domain\ValuesObject\ID;

interface CodificaOERepository {

    public function findByCod(ID $codOE): ?CodificaOE;

    /** @return CodificaOE[] */
    public function findAll(): array;

    /** @return CodificaOE[] */
    public function findByFornitore(ID $ragSoc): array;

    public function save(CodificaOE $codifica): void;

    public function delete(ID $codOE): void;
}

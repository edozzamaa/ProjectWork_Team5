<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\CodificaReg;

interface CodificaRegRepository {

    public function findByCod(string $codReg): ?CodificaReg;

    /** @return CodificaReg[] */
    public function findAll(): array;

    public function save(CodificaReg $codifica): void;

    public function delete(string $codReg): void;
}

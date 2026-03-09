<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\CodificaReg;
use src\Domain\ValuesObject\ID;

interface CodificaRegRepository {

    public function findByCod(ID $codReg): ?CodificaReg;

    /** @return CodificaReg[] */
    public function findAll(): array;

    public function save(CodificaReg $codifica): void;

    public function delete(ID $codReg): void;
}

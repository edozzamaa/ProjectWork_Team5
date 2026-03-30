<?php declare(strict_types=1);
namespace src\Application\Interfaces\IRepositories;

use src\Domain\Models\CodificaReg;
use src\Domain\ValueObjects\CodificaReg\CodificaRegId;

interface ICodificaRegRepository {

    public function findByCod(CodificaRegId $codReg): ?CodificaReg;

    /** @return CodificaReg[] */
    public function findAll(): array;

    public function save(CodificaReg $codifica): void;

    public function delete(CodificaRegId $codReg): void;
}

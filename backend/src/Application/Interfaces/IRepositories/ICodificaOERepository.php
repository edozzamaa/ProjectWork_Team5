<?php declare(strict_types=1);
namespace src\Application\Interfaces\IRepositories;

use src\Domain\Models\CodificaOE;
use src\Domain\ValueObjects\CodificaOE\CodificaOEId;
use src\Domain\ValueObjects\Fornitore\FornitoreId;

interface ICodificaOERepository {

    public function findByCod(CodificaOEId $codOE): ?CodificaOE;

    /** @return CodificaOE[] */
    public function findAll(): array;

    /** @return CodificaOE[] */
    public function findByFornitore(FornitoreId $ragSoc): array;

    public function save(CodificaOE $codifica): void;

    public function delete(CodificaOEId $codOE): void;
}

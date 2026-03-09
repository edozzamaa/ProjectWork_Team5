<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\Prodotto;
use src\Domain\Models\AttrProd;
use src\Domain\ValuesObject\ID;

interface ProdottoRepository {

    public function findByCod(ID $codProd): ?Prodotto;

    /** @return Prodotto[] */
    public function findAll(): array;

    /** @return Prodotto[] */
    public function findByCategoria(ID $codCat): array;

    public function save(Prodotto $prodotto): void;

    public function delete(ID $codProd): void;

    /** @return AttrProd[] */
    public function getAttributi(ID $codProd): array;

    public function saveAttributo(AttrProd $attrProd): void;

    public function deleteAttributo(ID $codProd, ID $codAttr): void;
}

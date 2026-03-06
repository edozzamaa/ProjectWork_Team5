<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\Prodotto;
use src\Domain\Models\AttrProd;

interface ProdottoRepository {

    public function findByCod(string $codProd): ?Prodotto;

    /** @return Prodotto[] */
    public function findAll(): array;

    /** @return Prodotto[] */
    public function findByCategoria(string $codCat): array;

    public function save(Prodotto $prodotto): void;

    public function delete(string $codProd): void;

    /** @return AttrProd[] */
    public function getAttributi(string $codProd): array;

    public function saveAttributo(AttrProd $attrProd): void;

    public function deleteAttributo(string $codProd, string $codAttr): void;
}

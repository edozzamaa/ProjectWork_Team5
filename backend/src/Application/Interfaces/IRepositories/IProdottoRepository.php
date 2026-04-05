<?php declare(strict_types=1);
namespace src\Application\Interfaces\IRepositories;

use src\Domain\Models\Prodotto;
use src\Domain\Models\AttrProd;
use src\Domain\ValueObjects\Prodotto\ProdottoId;
use src\Domain\ValueObjects\Categoria\CategoriaId;
use src\Domain\ValueObjects\Attributo\AttributoId;

interface IProdottoRepository {

    public function findByCod(ProdottoId $codProd): ?Prodotto;

    /** @return Prodotto[] */
    public function findAll(): array;

    /** @return Prodotto[] */
    public function findByCategoria(CategoriaId $codCat): array;

    public function save(Prodotto $prodotto): void;

    /** @param string[] $columns */
    public function update(Prodotto $prodotto, array $columns): void;

    public function delete(ProdottoId $codProd): void;

    /** @return AttrProd[] */
    public function getAttributi(ProdottoId $codProd): array;

    public function saveAttributo(AttrProd $attrProd): void;

    public function deleteAttributo(ProdottoId $codProd, AttributoId $codAttr): void;
}

<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\PosProd;
use src\Domain\ValuesObject\ID;

interface GiacenzaRepository {

    public function find(ID $codProd, ID $codArmadio, ID $codScaffale): ?PosProd;

    /** @return PosProd[] */
    public function findByProdotto(ID $codProd): array;

    /** @return PosProd[] */
    public function findByPosizione(ID $codArmadio, ID $codScaffale): array;

    /** @return PosProd[] */
    public function findAll(): array;

    public function giacenzaTotale(ID $codProd): int;

    public function save(PosProd $posProd): void;

    public function delete(ID $codProd, ID $codArmadio, ID $codScaffale): void;

    /**
     * @return array<int, array{codProd: string, qtaRiordino: int, qtaTotale: int}>
     */
    public function prodottiSottoSoglia(): array;
}

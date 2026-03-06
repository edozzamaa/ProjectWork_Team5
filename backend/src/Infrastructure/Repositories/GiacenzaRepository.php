<?php declare(strict_types=1);
namespace src\Infrastructure\Repositories;

use src\Domain\Models\PosProd;

interface GiacenzaRepository {

    public function find(string $codProd, string $codArmadio, string $codScaffale): ?PosProd;

    /** @return PosProd[] */
    public function findByProdotto(string $codProd): array;

    /** @return PosProd[] */
    public function findByPosizione(string $codArmadio, string $codScaffale): array;

    /** @return PosProd[] */
    public function findAll(): array;

    public function giacenzaTotale(string $codProd): int;

    public function save(PosProd $posProd): void;

    public function delete(string $codProd, string $codArmadio, string $codScaffale): void;

    /**
     * @return array<int, array{codProd: string, qtaRiordino: int, qtaTotale: int}>
     */
    public function prodottiSottoSoglia(): array;
}

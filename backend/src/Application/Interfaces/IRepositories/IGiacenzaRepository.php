<?php declare(strict_types=1);
namespace src\Application\Interfaces\IRepositories;

use src\Domain\Models\PosProd;
use src\Domain\ValueObjects\Prodotto\ProdottoId;
use src\Domain\ValueObjects\Armadio\ArmadioId;
use src\Domain\ValueObjects\Posizione\ScaffaleId;

interface IGiacenzaRepository {

    public function find(ProdottoId $codProd, ArmadioId $codArmadio, ScaffaleId $codScaffale): ?PosProd;

    /** @return PosProd[] */
    public function findByProdotto(ProdottoId $codProd): array;

    /** @return PosProd[] */
    public function findByPosizione(ArmadioId $codArmadio, ScaffaleId $codScaffale): array;

    /** @return PosProd[] */
    public function findAll(): array;

    public function giacenzaTotale(ProdottoId $codProd): int;

    public function save(PosProd $posProd): void;

    public function delete(ProdottoId $codProd, ArmadioId $codArmadio, ScaffaleId $codScaffale): void;

    /**
     * @return array<int, array{codProd: string, qtaRiordino: int, qtaTotale: int}>
     */
    public function prodottiSottoSoglia(): array;
}

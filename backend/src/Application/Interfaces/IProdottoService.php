<?php declare(strict_types=1);
namespace src\Application\Interfaces;

use src\Application\DTO\ProdottoDTO;
use src\Application\DTO\AttrProdDTO;
use src\Application\DTO\ScaricoProdottoResultDTO;

interface IProdottoService {

    /** @return ProdottoDTO[] */
    public function getAll(): array;

    public function getByCod(string $codProd): ?ProdottoDTO;

    /** @return ProdottoDTO[] */
    public function getByCategoria(string $codCat): array;

    public function crea(string $codProd, int $qtaRiordino = 0, ?string $codCat = null, ?string $codReg = null, ?string $codOE = null): void;

    public function aggiorna(string $codProd, int $qtaRiordino, ?string $codCat = null, ?string $codReg = null, ?string $codOE = null): void;

    public function elimina(string $codProd): void;

    /** @return AttrProdDTO[] */
    public function getAttributi(string $codProd): array;

    /**
     * @param array<string, string> $attributi
     */
    public function carico(string $codProd, string $codArmadio, string $codScaffale, int $qta, array $attributi = []): void;

    public function scarico(string $codProd, string $codArmadio, string $codScaffale, int $qta): ScaricoProdottoResultDTO;

    /** @return ProdottoDTO[] */
    public function cercaConGiacenza(): array;
}

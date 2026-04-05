<?php declare(strict_types=1);
namespace src\Application\Interfaces\IServices;

use src\Application\DTO\ProdottoDTO;
use src\Application\DTO\AttrProdDTO;
use src\Application\DTO\ScaricoProdottoResultDTO;

interface IProdottoService {

    /** @return ProdottoDTO[] */
    public function getAll(): array;

    public function getByCod(string $codProd): ?ProdottoDTO;

    /** @return ProdottoDTO[] */
    public function getByCategoria(string $codCat): array;

    public function createProdotto(string $codProd, int $qtaRiordino = 0, ?string $codCat = null, ?string $codReg = null, ?string $codOE = null): void;

    /** @param array<string, mixed> $fields */
    public function updateProdotto(string $codProd, array $fields): void;

    public function deleteProdotto(string $codProd): void;

    /** @return AttrProdDTO[] */
    public function getAttributi(string $codProd): array;

    /**
     * @param array<string, string> $attributi
     */
    public function loadProdotto(string $codProd, string $codArmadio, string $codScaffale, int $qta, array $attributi = []): void;

    public function unloadProdotto(string $codProd, string $codArmadio, string $codScaffale, int $qta): ScaricoProdottoResultDTO;

    /** @return ProdottoDTO[] */
    public function searchWithStock(): array;
}

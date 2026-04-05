<?php declare(strict_types=1);
namespace src\Application\Interfaces\IServices;

use src\Application\DTO\AttributoDTO;
use src\Application\DTO\AttrProdDTO;

interface IAttributoService {

    // ── CRUD Attributo ──

    /** @return AttributoDTO[] */
    public function getAll(): array;

    public function getByCod(string $codAttr): ?AttributoDTO;

    public function createAttributo(string $codAttr, string $nome): void;

    public function updateAttributo(string $codAttr, string $nome): void;

    public function deleteAttributo(string $codAttr): void;

    // ── Assegnazione Attributi a Prodotto ──

    public function assignToProdotto(string $codProd, string $codAttr, ?string $valore = null): void;

    public function removeFromProdotto(string $codProd, string $codAttr): void;

    /** @return AttrProdDTO[] */
    public function getAttributiProdotto(string $codProd): array;
}

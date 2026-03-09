<?php declare(strict_types=1);
namespace src\Application\Interfaces;

use src\Application\DTO\AttributoDTO;
use src\Application\DTO\AttrProdDTO;

interface IAttributoService {

    // ── CRUD Attributo ──

    /** @return AttributoDTO[] */
    public function getAll(): array;

    public function getByCod(string $codAttr): ?AttributoDTO;

    public function crea(string $codAttr, string $nome): void;

    public function aggiorna(string $codAttr, string $nome): void;

    public function elimina(string $codAttr): void;

    // ── Assegnazione Attributi a Prodotto ──

    public function assegnaAProdotto(string $codProd, string $codAttr, ?string $valore = null): void;

    public function rimuoviDaProdotto(string $codProd, string $codAttr): void;

    /** @return AttrProdDTO[] */
    public function getAttributiProdotto(string $codProd): array;
}

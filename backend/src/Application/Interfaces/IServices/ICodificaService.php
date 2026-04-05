<?php declare(strict_types=1);
namespace src\Application\Interfaces\IServices;

use src\Application\DTO\CodificaRegDTO;
use src\Application\DTO\CodificaOEDTO;

interface ICodificaService {

    // ── Codifica Regionale ──

    /** @return CodificaRegDTO[] */
    public function getAllReg(): array;

    public function getRegByCod(string $codReg): ?CodificaRegDTO;

    public function createReg(string $codReg, string $descrizione): void;

    public function updateReg(string $codReg, string $descrizione): void;

    public function deleteReg(string $codReg): void;

    // ── Codifica OE ──

    /** @return CodificaOEDTO[] */
    public function getAllOE(): array;

    public function getOEByCod(string $codOE): ?CodificaOEDTO;

    /** @return CodificaOEDTO[] */
    public function getOEByFornitore(string $ragSoc): array;

    public function createOE(string $codOE, string $descrizione, ?string $ragSoc = null): void;

    public function updateOE(string $codOE, string $descrizione, ?string $ragSoc = null): void;

    public function deleteOE(string $codOE): void;
}

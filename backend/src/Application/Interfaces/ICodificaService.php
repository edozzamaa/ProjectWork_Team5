<?php declare(strict_types=1);
namespace src\Application\Interfaces;

use src\Application\DTO\CodificaRegDTO;
use src\Application\DTO\CodificaOEDTO;

interface ICodificaService {

    // ── Codifica Regionale ──

    /** @return CodificaRegDTO[] */
    public function getAllReg(): array;

    public function getRegByCod(string $codReg): ?CodificaRegDTO;

    public function creaReg(string $codReg, string $descrizione): void;

    public function aggiornaReg(string $codReg, string $descrizione): void;

    public function eliminaReg(string $codReg): void;

    // ── Codifica OE ──

    /** @return CodificaOEDTO[] */
    public function getAllOE(): array;

    public function getOEByCod(string $codOE): ?CodificaOEDTO;

    /** @return CodificaOEDTO[] */
    public function getOEByFornitore(string $ragSoc): array;

    public function creaOE(string $codOE, string $descrizione, ?string $ragSoc = null): void;

    public function aggiornaOE(string $codOE, string $descrizione, ?string $ragSoc = null): void;

    public function eliminaOE(string $codOE): void;
}

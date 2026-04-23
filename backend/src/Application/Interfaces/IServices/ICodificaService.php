<?php declare(strict_types=1);
namespace src\Application\Interfaces\IServices;

use src\Application\DTO\CodificaRegDTO;
use src\Application\DTO\CodificaOEDTO;
use src\Application\DTO\Input\GetCodificaRegByCodInput;
use src\Application\DTO\Input\CreateCodificaRegInput;
use src\Application\DTO\Input\UpdateCodificaRegInput;
use src\Application\DTO\Input\DeleteCodificaRegInput;
use src\Application\DTO\Input\GetCodificaOEByCodInput;
use src\Application\DTO\Input\GetCodificaOEByFornitoreInput;
use src\Application\DTO\Input\CreateCodificaOEInput;
use src\Application\DTO\Input\UpdateCodificaOEInput;
use src\Application\DTO\Input\DeleteCodificaOEInput;

interface ICodificaService {

    // ── Codifica Regionale ──

    /** @return CodificaRegDTO[] */
    public function getAllReg(): array;

    public function getRegByCod(GetCodificaRegByCodInput $input): ?CodificaRegDTO;

    public function createReg(CreateCodificaRegInput $input): void;

    public function updateReg(UpdateCodificaRegInput $input): void;

    public function deleteReg(DeleteCodificaRegInput $input): void;

    // ── Codifica OE ──

    /** @return CodificaOEDTO[] */
    public function getAllOE(): array;

    public function getOEByCod(GetCodificaOEByCodInput $input): ?CodificaOEDTO;

    /** @return CodificaOEDTO[] */
    public function getOEByFornitore(GetCodificaOEByFornitoreInput $input): array;

    public function createOE(CreateCodificaOEInput $input): void;

    public function updateOE(UpdateCodificaOEInput $input): void;

    public function deleteOE(DeleteCodificaOEInput $input): void;
}

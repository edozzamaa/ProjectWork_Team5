<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use src\Application\Interfaces\IServices\ICodificaService;
use src\Presentation\Response\IResponse;

class CodificaController {

    public function __construct(
        private ICodificaService $service,
        private IResponse $response
    ) {}

    public function getAllReg(): void {
    }

    public function getRegByCod(string $codReg): void {
    }

    public function creaReg(): void {
    }

    public function aggiornaReg(string $codReg): void {
    }

    public function eliminaReg(string $codReg): void {
    }

    public function getAllOE(): void {
    }

    public function getOEByCod(string $codOE): void {
    }

    public function getOEByFornitore(string $ragSoc): void {
    }

    public function creaOE(): void {
    }

    public function aggiornaOE(string $codOE): void {
    }

    public function eliminaOE(string $codOE): void {
    }
}

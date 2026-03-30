<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use src\Application\Interfaces\IServices\IAttributoService;
use src\Presentation\Response\IResponse;

class AttributoController {

    public function __construct(
        private IAttributoService $service,
        private IResponse $response
    ) {}

    public function getAll(): void {
    }

    public function getByCod(string $codAttr): void {
    }

    public function crea(): void {
    }

    public function aggiorna(string $codAttr): void {
    }

    public function elimina(string $codAttr): void {
    }

    public function assegnaAProdotto(string $codProd): void {
    }

    public function rimuoviDaProdotto(string $codProd, string $codAttr): void {
    }

    public function getAttributiProdotto(string $codProd): void {
    }
}

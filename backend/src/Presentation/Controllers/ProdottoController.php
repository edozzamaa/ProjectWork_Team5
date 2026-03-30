<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use src\Application\Interfaces\IServices\IProdottoService;
use src\Presentation\Response\IResponse;

class ProdottoController {

    public function __construct(
        private IProdottoService $service,
        private IResponse $response
    ) {}

    public function getAll(): void {
    }

    public function getByCod(string $codProd): void {
    }

    public function getByCategoria(string $codCat): void {
    }

    public function cercaConGiacenza(): void {
    }

    public function crea(): void {
    }

    public function aggiorna(string $codProd): void {
    }

    public function elimina(string $codProd): void {
    }

    public function getAttributi(string $codProd): void {
    }

    public function carico(): void {
    }

    public function scarico(): void {
    }
}

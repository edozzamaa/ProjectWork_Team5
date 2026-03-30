<?php declare(strict_types=1);

namespace src\Presentation\Controllers;

use src\Application\Interfaces\IServices\ICategoriaService;
use src\Presentation\Response\IResponse;

class CategoriaController {

    public function __construct(
        private ICategoriaService $service,
        private IResponse $response
    ) {}

    public function getAll(): void {
    }

    public function getByCod(string $codCat): void {
    }

    public function crea(): void {
    }

    public function aggiorna(string $codCat): void {
    }

    public function elimina(string $codCat): void {
    }
}
